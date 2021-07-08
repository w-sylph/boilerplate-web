<template>
    <div class="d-inline-block">
        <button
            type="button"
            class="btn btn-success"
            @click="open"
        >
            <i class="fa fa-upload" /> Import
        </button>

        <modal v-model="showModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                <form-request
                    :submit-url="submitUrl"
                    confirm-dialog
                    sync-on-success
                    validate
                    reset-on-success
                    disable-success-response
                    @load="load"
                    @success="success"
                    @error="showErrors"
                >
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>{{ label }}</label>
                                <div class="custom-file text-left">
                                    <input
                                        :id="id"
                                        type="file"
                                        class="custom-file-input"
                                        name="import_file"
                                        accept=".csv, .xls, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                        :class="[ invalidClass ]"
                                        @change="change"
                                    >
                                    <label
                                        class="custom-file-label"
                                        style="overflow: hidden;"
                                        :for="id"
                                    >{{ fileName || 'Upload file' }}</label>
                                </div>
                                <span
                                    v-if="isInvalid"
                                    class="d-block invalid-feedback"
                                >{{ invalidFormMessage }}</span>

                                <div
                                    v-if="fileUrl"
                                    class="mt-3"
                                >
                                    <p class="mb-0">
                                        Click <a :href="fileUrl">here</a> for a sample file to be imported.
                                    </p>
                                </div>
                            </div>


                            <div
                                v-if="array_count(importErrors) > 0"
                                class="col-12"
                            >
                                <p>Error(s) encountered while importing: <span class="badge badge-danger">{{ array_count(importErrors) }}</span></p>
                                <ul style="max-height: 200px; overflow-y: auto;">
                                    <li
                                        v-for="(importError, index) in importErrors"
                                        :key="'import-error-' + index"
                                        class="text-danger"
                                    >
                                        <span class="d-block text-danger">Item {{ importError.row }}</span>
                                        <ul>
                                            <li
                                                v-for="(error_columns, error_item_index) in importError.errors"
                                                :key="'import-error-item-' + error_item_index"
                                                class="text-danger"
                                            >
                                                {{ error_columns.join(', ').replace('.', '') }}
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light border"
                            @click="close"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Import
                        </button>
                    </div>
                </form-request>
            </template>
        </modal>
    </div>
</template>

<script>
import Modal from 'Components/dialogs/Modal.vue';

import InputMixin from 'Components/inputs/mixin.js';

import ArrayMixin from 'Mixins/array.js';
import FormMixin from 'Mixins/form.js';
import LoaderMixin from 'Mixins/loader.js';
import ResponseMixin from 'Mixins/response.js';
import ErroMixin from 'Mixins/error.js';

export default {
    name: 'import-form',

    components: {
        Modal
    },

    mixins: [ LoaderMixin, FormMixin, ArrayMixin, ResponseMixin, ErroMixin, InputMixin ],

    props: {
        title: {
            default: 'Import',
            type: String
        },

        label: {
            default: null,
            type: String
        },

        fileUrl: {
            default: null,
            type: String
        }
    },

    data: () => ({
        fileName: null,
        importErrors: [],
        showModal: false,
        invalidFormMessage: null
    }),

    computed: {
        id() {
            return 'import-form-' + this._uid;
        }
    },

    methods: {
        open() {
            this.showModal = true;
        },

        close() {
            this.showModal = false;
        },

        change(event) {
            let files = event.target.files;
            this.fileName = null;
            this.resetError();

            if (files.length > 0) {
                this.fileName = files[0].name;
            }
        },

        success(response) {
            let data = response.data;
            let errors = Object.values(data.errors);
            let message = data.message;
            this.fileName = null;
            this.importErrors = [];
            this.errors = [];

            if (this.array_count(errors)) {
                this.invalidFormMessage = message;
                this.invalid = true;
                this.importErrors = errors;
            } else {
                this.close();
                this.parseSuccess(message);
            }

            this.$emit('success');
        },

        showErrors(error) {
            this.invalid = true;
            this.setErrors(error);

            this.$nextTick(() => {
                this.invalidFormMessage = this.getError('import_file');
            });
        },

        resetError() {
            this.invalid = false;
            this.invalidFormMessage = null;
        }
    }
};

</script>