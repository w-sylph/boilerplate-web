<template>
    <div
        class="form-group"
        :class="[ invalidClass ]"
    >
        <!-- Single image for default type -->
        <div
            v-if="(value || url) && !multiple"
            class="row align-items-start"
        >
            <div class="col-sm-12 mb-2">
                <div class="d-inline-block align-top">
                    <a
                        :href="value"
                        target="_blank"
                    >
                        <img
                            v-if="value"
                            :src="renderImage(value)"
                            class="img-thumbnail"
                            width="120px"
                            height="auto"
                        >
                        <small
                            v-if="url && value"
                            class="d-block text-muted align-top text-sm-center"
                        >(Current File)</small>
                    </a>
                </div>
                <div class="d-inline-block align-top">
                    <img
                        v-if="url"
                        :src="url ? url : value"
                        class="img-thumbnail"
                        width="120px"
                        height="auto"
                    >
                    <small
                        v-if="url"
                        class="d-block text-muted align-top text-spm-center"
                    >(New File)</small>
                </div>
            </div>
        </div>

        <!-- Images for multiple type -->
        <div v-if="multiple">
            <small
                v-if="array_count(value) > 0"
                class="d-block text-muted align-top mb-2"
            >({{ sortUrl ? 'Drag to sort current files' : 'Current files' }})</small>
            <div
                v-if="value"
                ref="sortable"
                class="d-block align-top"
            >
                <template v-for="(image, index) in values">
                    <div
                        :key="'multiple-image-' + index"
                        class="d-inline-block position-relative mr-2"
                        :data-id="image[imageValue]"
                    >
                        <a
                            class="d-inline-block border border-dark"
                            :href="image[imageSrc]"
                            target="_blank"
                        >
                            <div
                                class="bg-cover"
                                style="width: 120px; height: 120px;"
                                :style="'background: url(' + renderImage(image[imageSrc]) + ');'"
                            />
                        </a>
                        <button
                            type="button"
                            class="btn btn-sm btn-danger remove-button"
                            :disabled="disabled"
                            @click="showDialog(image[imageValue])"
                        >
                            <i class="fa fa-times" />
                        </button>
                    </div>
                </template>
            </div>
            <div
                v-if="array_count(files) > 0"
                class="d-block align-top"
            >
                <small class="d-block text-muted align-top">(New files)</small>
                <template v-for="(file, index) in files">
                    <div
                        :key="'file-name-' + index "
                        class="border border-dark bg-cover d-inline-block mr-2"
                        style="width: 120px; height: 120px;"
                        :style="'background: url('+ file +');'"
                    />
                </template>
            </div>
            <div class="mb-1" />
        </div>

        <!-- input dom element -->
        <label v-if="label">{{ label }} <small
            v-if="labelNote"
            :class="labelNoteClass"
        >{{ labelNote }}</small></label>
        <div class="input-group">
            <div
                v-if="hasFiles"
                class="input-group-prepend"
            >
                <button
                    type="button"
                    class="btn btn-danger"
                    :disabled="disabled"
                    @click="clear"
                >
                    <i class="fa fa-times" />
                </button>
            </div>

            <div class="custom-file">
                <input
                    ref="file"
                    v-bind="$attrs"
                    :name="name"
                    :disabled="!editable"
                    type="file"
                    :accept="inputAcceptAttr"
                    :multiple="multiple"
                    class="custom-file-input"
                    :class="[ invalidClass ]"
                    v-on="inputListeners"
                >
                <label
                    class="custom-file-label"
                    style="overflow: hidden;"
                >
                    <template v-if="array_count(images) > 0">
                        <template
                            v-for="image in images"
                        >{{ image.name }} </template>
                    </template>
                    <template v-else>{{ placeholder }}</template>
                </label>
            </div>

            <!-- input group append -->
            <div
                v-if="$slots['append']"
                class="input-group-append"
            >
                <slot name="append" />
            </div>
        </div>

        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

import ResponseMixin from '../../mixins/response.js';
import ArrayMixin from '../../mixins/array.js';
import SortableMixin from '../../mixins/sortable.js';
import ConfirmProps from 'Mixins/confirm/props.js';

export default {
    name: 'file-picker',

    mixins: [ InputMixin, ResponseMixin, ArrayMixin, SortableMixin, ConfirmProps ],

    props: {
        value: {
            default: null,
            type: [ Array, String ]
        },

        accept: {
            default: null,
            type: String
        },

        type: {
            default: null,
            type: String,
            validator: (value) => {
                let result = true;

                if ([ 'file', 'image' ].indexOf(value) === -1) {
                    result = false;
                    console.error('[Vue warn]: Invalid prop: value must be "file" or "image".');
                }

                return result;
            }
        },

        multiple: {
            default: false,
            type: Boolean
        },

        placeholder: {
            default: 'Choose File',
            type: String
        },

        confirm: {
            default: true,
            type: Boolean
        },

        imageSrc: {
            default: 'path',
            type: String
        },

        imageValue: {
            default: 'id',
            type: String
        },

        max: {
            default: null,
            type: [ String, Number ]
        },

        min: {
            default: null,
            type: [ String, Number ]
        },

        removeUrl: {
            default: null,
            type: String
        },

        title: {
            default: 'Remove file',
            type: String
        },

        message: {
            default: 'Are you sure you want to remove this file?',
            type: String
        }
    },

    data: () => ({
        values: [],
        images: [],
        url: null,

        files: [],
        loading: false,
        hasRemoved: false
    }),

    computed: {
        inputAcceptAttr() {
            if (! this.accept) {
                switch (this.type) {
                    case 'image':
                        return 'image/*';
                    case 'file':
                        return '';
                    default:
                        return '';
                }
            }

            return this.accept;
        },

        editable() {
            return !this.disabled && !this.loading;
        },

        hasFiles() {
            return this.url || this.array_count(this.files);
        },

        getFilePlaceholder() {
            return '/images/file-placeholder.png';
        },

        inputCustomListeners() {
            return {
                change: (event) => {
                    let blob;

                    if (this.invalid) {
                        this.invalid = false;
                    }

                    this.files = [];
                    let files = event.target.files;

                    if (files.length > 0) {
                        if (! this.validateCount(files)) {
                            return;
                        }

                        if (! this.multiple) {
                            blob = this.getFilePlaceholder;

                            if (this.isImage(files[0].type)) {
                                blob = URL.createObjectURL(files[0]);
                            }

                            this.url = blob;
                            this.images = [ { name: files[0].name } ];
                        } else {
                            for (let i = files.length - 1; i >= 0; i--) {
                                blob = this.getFilePlaceholder;

                                if (this.isImage(files[i].type)) {
                                    blob = URL.createObjectURL(files[i]);
                                }

                                this.files.push(blob);
                                this.images.push({ name: files[i].name });
                            }
                        }
                    }

                    this.$emit('change', event);
                }
            };
        }
    },

    watch: {
        value(value) {
            if (! this.hasRemoved) {
                this.files = [];
                this.clear();
            }

            setTimeout(() => {
                this.setupSortable();
                this.values = value;
            }, 50);

            this.hasRemoved = false;
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            if (this.multiple) {
                this.files = [];
            } else {
                this.files = null;
            }
        },

        showDialog(id) {
            if (! this.confirm) {
                this.remove(id, false);
                return;
            }
            if (! this.validateCount(this.files, true)) {
                return;
            }

            let message = {
                title: this.title,
                body: this.message
            };

            let options = {
                loader: true,
                okText: this.okText,
                cancelText: this.cancelText,
                animation: 'fade',
                type: this.dialogType
            };

            this.$dialog.confirm(message, options)
                .then((dialog) => {
                    this.remove(id, dialog);
                }).catch(() => {

                });
        },

        remove(id, dialog = null) {
            if (this.loading) {
                return;
            }
            if (! this.validateCount(this.files, true)) {
                return;
            }

            this.loading = true;

            axios.post(this.removeUrl, { id: id })
                .then(() => {
                    this.hasRemoved = true;

                    this.values = this.values.filter(obj => obj.id != id);

                    this.$emit('remove');
                }).catch(error => {
                    this.parseError(error);
                }).then(() => {
                    this.loading = false;
                    if (dialog) {
                        dialog.loading(false);
                        dialog.close();
                    }
                });
        },

        clear() {
            this.$refs.file.value = null;
            this.images = [];
            this.files = [];
            this.url = null;
        },

        isImage(mime) {
            let validMimes = [ 'image/jpg', 'image/png', 'image/jpeg', 'image/gif', 'image/bmp' ];
            return this.inArray(mime, validMimes);
        },

        renderImage(url) {
            let result = this.getFilePlaceholder;
            let imageSrc = url ? url.toLowerCase() : url;

            if (imageSrc.match(/\.(jpeg|jpg|gif|png)$/) != null) {
                result = url;
            }

            return result;
        },

        validateCount(files, removeImage = false) {
            if (this.multiple) {
                let count = this.array_count(this.value);

                if (this.max && !removeImage) {
                    if ((this.array_count(files) + count) > this.max) {
                        this.parseError('You can only have maximum of ' + this.max + ' images.');
                        this.clear();
                        return false;
                    }
                }

                if (this.min) {
                    let minus = removeImage ? 1 : 0;
                    if ((this.array_count(files) + count - minus) < this.min) {
                        this.parseError('You can only have minimum of ' + this.min + ' images.');
                        this.clear();
                        return false;
                    }
                }

                return true;
            }

            return true;
        }
    }
};
</script>

<style type="text/css" scoped>
.bg-cover {
	background-size: cover !important;
    background-repeat: no-repeat !important;
    background-position: center center !important;
}

.remove-button {
	position: absolute;
	top: 0;
	right: 0;
}

.form-group.is-invalid .invalid-feedback {
    display: block;
}
</style>