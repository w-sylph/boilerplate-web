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
            <template
                v-if="!noTitle"
                #header
            >
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

                <selector
                    v-model="item.page_id"
                    class="col-sm-12 col-md-6"
                    name="page_id"
                    label="Page"
                    label-note="(Warning: editing this may cause issues)"
                    :items="pages"
                    item-value="id"
                    item-text="name"
                    empty-text="None"
                    placeholder="Please select a page"
                />

                <selector
                    v-model="item.type"
                    class="col-sm-12 col-md-6"
                    name="type"
                    label="Type"
                    label-note="(Warning: editing this may cause issues)"
                    :items="types"
                    placeholder="Please select a type"
                />
            </div>

            <page-item-content
                :type="item.type"
                name="content"
                label="Content"
                :value="item.content"
            />

            <template #footer>
                <a
                    v-if="item.parentUrl"
                    :href="item.parentUrl"
                    target="_blank"
                    class="btn btn-secondary text-white"
                >View Parent</a>

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
                    :message="'Are you sure you want to archive Page Item #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore Page Item #' + item.id + '?'"
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

import PageItemContent from './PageItemContent.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Selector from '../../../components/inputs/Selector.vue';
import TextField from '../../../components/inputs/TextField.vue';

export default {
    name: 'page-item-view',

    components: {
        PageItemContent,
        ActionButton,
        Selector,
        TextField
    },

    mixins: [ CrudMixin ],

    props: {
        pageItem: {
            default: null,
            type: Object
        },

        noTitle: {
            default: false,
            type: Boolean
        }
    },

    data() {
        return {
            types: [],
            pages: []
        };
    },

    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
            this.pages = data.pages ? data.pages : this.pages;
            this.types = data.types ? data.types : this.types;
        }
    }
};
</script>