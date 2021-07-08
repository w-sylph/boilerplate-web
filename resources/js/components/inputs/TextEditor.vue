<template>
    <div
        class="form-group"
        :class="[ invalidClass ]"
    >
        <label v-if="label">
            {{ label }}
            <small
                v-if="labelNote"
                :class="labelNoteClass"
            >
                {{ labelNote }}
            </small>
        </label>
        <textarea
            ref="elem"
            :name="name"
            type="text"
            class="form-control input-sm"
        />
        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
/* eslint-disable-next-line no-undef */
require('../../plugins/ckeditor/index.js');
import { UploadAdapter } from '../../plugins/ckeditor/UploadAdapter.js';
import InputMixin from './mixin.js';

export default {
    name: 'text-editor',

    mixins: [ InputMixin ],

    data: () => ({
        editor: null,
        hasInit: false
    }),

    computed: {
        /* Disable automatic changes due to conflict with other components using 3rd party plugin such as ckeditor */
        disableChangeEvent() {
            return true;
        }
    },

    watch: {
        modelValue(value) {
            if (! value) {
                value = '';
            }

            if (this.editor) {
                if (this.editor.getData() != value) {
                    this.editor.setData(value);
                }
            }

            this.inputChange(value);
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            /* eslint-disable-next-line no-undef */
            ClassicEditor
                .create(this.$refs.elem, {
                    placeholder: this.placeholder,
                    extraPlugins: [ this.uploadAdapterPlugin ],
                    mediaEmbed: {
                        previewsInData: true
                    }
                })
                .then(editor => {
                    if (! this.editor) {
                        this.editor = editor;
                    }

                    editor.model.document.on('change:data', () => {
                        if (this.invalid) {
                            this.invalid = false;
                        }

                        this.inputChange(editor.getData());
                    });

                    if (! this.hasInit) {
                        this.hasInit = true;
                        // console.log(this.modelValue);
                        editor.setData(this.modelValue);
                    }
                }).catch(() => {
                    // console.log(error);
                });
        },

        uploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        },

        inputChange(value) {
            if (this.invalid) {
                this.invalid = false;
            }

            this.$emit('input', value);
        }
    }
};
</script>

<style type="text/css">
.form-group.is-invalid .ck.ck-content,
.form-group.is-invalid .ck.ck-toolbar {
    border-color: #dc3545 !important;
}

.ck.ck-content.ck-editor__editable {
    min-height: 200px;
    /* max-height: 200px; */
}
</style>