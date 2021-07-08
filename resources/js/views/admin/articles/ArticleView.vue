<template>
    <form-request
        :submit-url="submitUrl"
        confirm-dialog
        sync-on-success
        validate
        @load="load"
        @success="fetch"
        @error="setErrors"
    >
        <card>
            <template #header>
                Basic Information
            </template>

            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label>Name</label>
                    <input
                        v-model="item.name"
                        name="name"
                        type="text"
                        class="form-control"
                    >
                    <span class="invalid-feedback" />
                </div>

                <date-picker
                    v-model="item.published_at"
                    class="form-group col-sm-12 col-md-6"
                    label="Published Date"
                    name="published_at"
                    placeholder="Choose a date"
                />
            </div>

            <div class="row">
                <text-editor
                    v-model="item.description"
                    class="col-sm-12"
                    label="Description"
                    name="description"
                    row="5"
                />
            </div>

            <div class="row">
                <file-picker
                    :value="item.path"
                    class="form-group col-sm-12 col-md-12 mt-2"
                    type="image"
                    label="Image"
                    name="file_path"
                    placeholder="Choose a File"
                />

                <file-picker
                    :value="images"
                    class="form-group col-sm-12 col-md-12 mt-2"
                    label="Images"
                    name="images[]"
                    placeholder="Choose Files"
                    multiple
                    sortable
                    :sort-url="sortUrl"
                    :remove-url="item.removeImageUrl"
                    max="3"
                    min="1"
                    :error="getError('images')"
                />
            </div>
        </card>

        <meta-form
            :item="meta"
        />

        <card>
            <template #footer>
                <action-button
                    v-if="item.archiveUrl && item.restoreUrl"
                    color="btn-danger"
                    alt-color="btn-warning"
                    :action-url="item.archiveUrl"
                    :alt-action-url="item.restoreUrl"
                    label="Archive"
                    alt-label="Restore"
                    :show-alt="item.deleted_at"
                    confirm-dialog
                    title="Archive Item"
                    alt-title="Restore Item"
                    :message="'Are you sure you want to archive article #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore article #' + item.id + '?'"
                    :disabled="loading"
                    @load="load"
                    @success="fetch"
                    @error="fetch"
                />

                <action-button
                    type="submit"
                    :disabled="loading"
                    class="btn-primary"
                >
                    Save Changes
                </action-button>
            </template>
        </card>

        <loader :loading="loading" />
    </form-request>
</template>

<script>
import CrudMixin from '../../../mixins/crud.js';
import ErrorMixin from '../../../mixins/error.js';

import MetaForm from '../../../components/forms/MetaForm.vue';

import ActionButton from '../../../components/buttons/ActionButton.vue';
import FilePicker from '../../../components/inputs/FilePicker.vue';
import TextEditor from '../../../components/inputs/TextEditor.vue';
import DatePicker from '../../../components/inputs/DatePicker.vue';

export default {
    name: 'article-view',

    components: {
        MetaForm,
        ActionButton,
        FilePicker,
        TextEditor,
        DatePicker
    },

    mixins: [ ErrorMixin, CrudMixin ],

    data: () => ({
        items: [],
        images: [],
        meta: {}
    }),

    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
            this.meta = data.meta ? data.meta : this.meta;
            this.images = data.images ? data.images : this.images;
        }
    }
};
</script>