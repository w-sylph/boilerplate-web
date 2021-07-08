<template>
    <form-request
        :submit-url="submitUrl"
        confirm-dialog
        sync-on-success
        validate
        @load="load"
        @success="fetch"
    >
        <card>
            <template #header>
                Basic Information
            </template>
            <div class="row">
                <text-field
                    v-model="item.name"
                    class="col-12 col-md-6"
                    label="Name"
                    name="name"
                />

                <text-field
                    v-model="item.slug"
                    class="col-12 col-md-6"
                    label="Slug"
                    name="slug"
                    label-note="(Warning: editing this may cause issues)"
                />
            </div>
        </card>

        <card>
            <template #header>
                Page Content
            </template>

            <template v-if="array_count(page_items)">
                <page-item-content
                    v-for="page_item in page_items"
                    :key="page_item.id"
                    v-model="page_item.content"
                    :type="page_item.type"
                    :name="'content[' + page_item.id + ']'"
                    :label="page_item.name"
                    :note="page_item.slug"
                />
            </template>
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
                    :message="'Are you sure you want to archive Page #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore Page #' + item.id + '?'"
                    :disabled="loading"
                    @load="load"
                    @success="fetch"
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
import ArrayMixin from '../../../mixins/array.js';

import ActionButton from '../../../components/buttons/ActionButton.vue';
import MetaForm from '../../../components/forms/MetaForm.vue';
import PageItemContent from './PageItemContent.vue';

import TextField from '../../../components/inputs/TextField.vue';

export default {
    name: 'page-view',

    components: {
        ActionButton,
        MetaForm,
        PageItemContent,
        TextField
    },

    mixins: [ CrudMixin, ArrayMixin ],

    data() {
        return {
            page_items: [],
            meta: {}
        };
    },
    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
            this.meta = data.meta ? data.meta : this.meta;
            this.page_items = data.page_items ? data.page_items : this.page_items;
        }
    }
};
</script>