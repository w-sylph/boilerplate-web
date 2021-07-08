<template>
    <div class="form-group">
        <label
            v-if="label"
            class="theme--text"
        >
            {{ label }}
            <small
                v-if="labelNote"
                class="theme--text"
                :class="labelNoteClass"
            >{{ labelNote }}</small>
            <small
                v-if="hint"
                ref="hint"
                class="fas fa-question-circle ml-1"
            />
        </label>

        <label
            :for="id"
            class="dropzone w-100 cursor-pointer"
            @dragover.prevent
            @drop.prevent="drop"
        >
            <div class="dz-message">
                <button
                    type="button"
                    class="dz-button"
                >
                    {{ placeholder }}
                </button>
            </div>
            <input
                :id="id"
                type="file"
                class="dz-hidden"
                multiple
                :accept="inputAcceptAttr"
                @change="select"
            >
        </label>

        <media-library
            ref="media"
            label="Media Library"
            :name="`media_${name}`"
            :fetch-url="mediaUrl"
            @select="addMediaFiles"
        />

        <div
            v-show="hasFiles"
            ref="sortable"
            class="dropzone dz-borderless"
        >
            <div
                v-for="(file, index) in currentFiles"
                :key="`current-file-${index}`"
                class="dz-preview text-center"
                draggable="true"
                :data-id="file.id"
            >
                <a
                    :href="file.path"
                    target="_blank"
                >
                    <div class="dz-image">
                        <img
                            :src="renderImage(file.path)"
                        >
                    </div>
                    <div class="dz-details">
                        <div class="dz-size">
                            <span>{{ file.size | kilobyte }} KB</span>
                        </div>
                        <div class="dz-filename">
                            <span>{{ file.name }}</span>
                        </div>
                    </div>
                </a>
                <a
                    class="text-center"
                    href="#"
                    @click.prevent="showDialog(file.id, 'current')"
                >
                    Remove file
                </a>
            </div>

            <div
                v-for="(file, index) in newFiles"
                :key="`new-file-${index}`"
                class="dz-preview text-center"
                draggable="true"
            >
                <div class="dz-image">
                    <img
                        :src="renderImage(file.path)"
                    >
                </div>
                <div class="dz-details">
                    <div class="dz-size">
                        <span>{{ file.size | kilobyte }} KB</span>
                    </div>
                    <div class="dz-filename">
                        <span>{{ file.name }}</span>
                    </div>
                </div>
                <a
                    class="text-center"
                    href="#"
                    @click.prevent="showDialog(file.id, 'new')"
                >
                    Remove file
                </a>

                <input
                    v-if="storeType == 'binary'"
                    :key="`new-file-input-${index}`"
                    :name="name"
                    type="hidden"
                    :value="file.path"
                >

                <input
                    v-if="storeType == 'file'"
                    :key="`new-file-input-${index}`"
                    :name="name"
                    type="hidden"
                    :value="file.id"
                >
            </div>

            <div
                v-for="(file, index) in mediaFiles"
                :key="`media-file-${index}`"
                class="dz-preview text-center"
                draggable="true"
                :data-id="file.id"
            >
                <a
                    :href="file.path"
                    target="_blank"
                >
                    <div class="dz-image">
                        <img
                            :src="renderImage(file.path)"
                        >
                    </div>
                    <div class="dz-details">
                        <div class="dz-size">
                            <span>{{ file.size | kilobyte }} KB</span>
                        </div>
                        <div class="dz-filename">
                            <span>{{ file.name }}</span>
                        </div>
                    </div>
                </a>
                <a
                    class="text-center"
                    href="#"
                    @click.prevent="showDialog(file.id, 'media')"
                >
                    Remove file
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import InputMixin from './mixin.js';
import ConfirmProps from 'Mixins/confirm/props.js';
import StringMixin from 'Mixins/string.js';
import ArrayMixin from 'Mixins/array.js';
import LoaderMixin from 'Mixins/loader.js';
import BinaryMixin from 'Mixins/binary.js';
import SortableMixin from 'Mixins/sortable.js';

import MediaLibrary from 'Components/inputs/MediaLibrary.vue';

export default {
    name: 'file-dropzone',

    filters: {
        kilobyte(value) {
            let result = 0;

            if (Number.isInteger(parseInt(value))) {
                result = Math.round(value / 1024);
            }

            return result;
        }
    },

    components: {
        MediaLibrary
    },

    mixins: [ LoaderMixin, InputMixin, ConfirmProps, BinaryMixin, StringMixin, ArrayMixin, SortableMixin ],

    props: {
        accept: {
            default: null,
            type: String
        },

        files: {
            default: null,
            type: Array
        },

        placeholder: {
            default: 'Select or drop files here to upload',
            type: String
        },

        removeUrl: {
            default: null,
            type: String
        },

        mediaUrl: {
            default: null,
            type: String
        },

        type: {
            default: 'any',
            type: String,
            validator: (value) => {
                return [ 'any', 'file', 'image' ].indexOf(value) !== -1;
            }
        },

        storeType: {
            default: 'binary',
            type: String,
            validator: (value) => {
                let result = true;

                if ([ 'file', 'binary' ].indexOf(value) === -1) {
                    result = false;
                    console.error('Store type must be file or binary');
                }

                return result;
            }
        },

        confirm: {
            default: true,
            type: Boolean
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
        elem: null,
        token: null,

        newFiles: [],
        currentFiles: [],
        mediaFiles: []
    }),

    computed: {
        id() {
            return `dz-${this._uid}`;
        },

        hasFiles() {
            return this.array_count(
                this.currentFiles
                    .concat(this.newFiles)
                    .concat(this.mediaFiles)
            );
        },

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

        getFilePlaceholder() {
            return '/images/file-placeholder.png';
        }
    },

    watch: {
        files(value) {
            this.currentFiles = value;
            this.newFiles = [];
            this.mediaFiles = [];
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            /* Set csrf token */
            let token = document.head.querySelector('meta[name="csrf-token"]');

            if (token) {
                this.token = token.content;
            } else {
                console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
            }
        },

        select(event) {
            this.store(event.target.files);
        },

        drop(event) {
            this.store(event.dataTransfer.files);
        },

        store(files) {
            if (files.length < 1) {
                return;
            }

            if (this.type == 'image') {
                for (let i = 0; i < files.length; i++) {
                    let mime = files[i].type;
                    if (! this.isImage(mime)) {
                        return;
                    }
                }
            }

            switch (this.storeType) {
                case 'binary':
                    this.storeAsBinary(files);
                    break;
                case 'file':
                    this.storeAsFile(files);
                    break;
            }
        },

        storeAsBinary(files) {
            for (let i = 0; i < files.length; i++) {
                let file = files[i];

                this.getBase64(file)
                    .then(binary => {
                        let thumbnail = binary;

                        if (! this.isImage(file.type)) {
                            thumbnail = this.getFilePlaceholder;
                        }

                        let obj = {
                            id: this.uuidv4(),
                            name: file.name,
                            size: file.size,
                            path: thumbnail
                        };

                        this.newFiles.push(obj);
                    });
            }
        },

        storeAsFile(files) {
            this.load(true);
            let params = new FormData();

            for (let i = 0; i < files.length; i++) {
                params.append('files[]', files[i]);
            }

            axios.post('/file/upload', params)
                .then(response => {
                    let data = response.data;
                    this.newFiles = this.newFiles.concat(data.files);
                }).catch(error => {
                    console.log(error);
                }).then(() => {
                    this.load(false);
                });
        },

        remove(id, imageType, dialog = null) {
            switch (imageType) {
                case 'new':
                    this.newFiles = this.newFiles.filter(obj => obj.id != id);

                    if (this.storeType == 'file') {
                        this.load(true);
                        axios.post('/file/remove-temp', { id: id })
                            .then(() => {
                                this.currentFiles = this.currentFiles.filter(obj => obj.id != id);
                            }).catch(error => {
                                console.log(error);
                            }).then(() => {
                                if (dialog) {
                                    dialog.loading(false);
                                    dialog.close();
                                }

                                this.load(false);
                            });
                    } else {
                        if (dialog) {
                            dialog.loading(false);
                            dialog.close();
                        }
                    }
                    break;
                case 'current':
                    if (this.removeUrl) {
                        this.load(true);
                        axios.post(this.removeUrl, { id: id })
                            .then(() => {
                                this.currentFiles = this.currentFiles.filter(obj => obj.id != id);
                            }).catch(error => {
                                console.log(error);
                            }).then(() => {
                                if (dialog) {
                                    dialog.loading(false);
                                    dialog.close();
                                }

                                this.load(false);
                            });
                    }
                    break;
                case 'media':
                    this.mediaFiles = this.mediaFiles.filter(obj => obj.id != id);

                    this.$nextTick(() => {
                        this.$refs.media.setMedia(this.mediaFiles);

                        if (dialog) {
                            dialog.loading(false);
                            dialog.close();
                        }
                    });
                    break;
            }
        },

        showDialog(id, imageType) {
            if (! this.confirm) {
                this.remove(id, imageType, false);
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
                    this.remove(id, imageType, dialog);
                }).catch(() => {

                });
        },

        isImage(mime) {
            let validMimes = [ 'image/jpg', 'image/png', 'image/jpeg', 'image/gif', 'image/bmp' ];
            return this.inArray(mime, validMimes);
        },

        renderImage(url) {
            let result = this.getFilePlaceholder;

            if (url) {
                if (url.match(/\.(jpeg|jpg|gif|png)$/) != null) {
                    result = url;
                }
            }

            return result;
        },

        addMediaFiles(files) {
            this.mediaFiles = files;
        }
    }
};
</script>

<style scoped>
.dropzone {
    border: 2px dashed #0087F7;
    padding: 0;
    position: relative;
    z-index: 1;
}

.dropzone.dz-borderless {
    border: none;
}

.dropzone .dz-preview {
    margin-left: 0;
}

.dz-file-uploader {
    opacity: 0;
    visibility: hidden;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}

.dz-image img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}

.dz-button {
    position: relative;
    z-index: -1;
    font-size: 20px !important;
    color: #646C7F !important;
}

.dz-hidden {
    opacity: 0;
    visibility: hidden;
}
</style>