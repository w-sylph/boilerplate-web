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
                    class="col-sm-12"
                    label="Name"
                    name="name"
                />

                <text-area
                    v-model="item.description"
                    class="col-sm-12"
                    label="Description"
                    name="description"
                />
            </div>

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
                    :message="'Are you sure you want to archive ' + item.name + '?'"
                    :alt-message="'Are you sure you want to restore ' + item.name + '?'"
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
import ActionButton from '../../../components/buttons/ActionButton.vue';
import TextField from '../../../components/inputs/TextField.vue';
import TextArea from '../../../components/inputs/TextArea.vue';

export default {
    name: 'role-view',

    components: {
        ActionButton,
        TextField,
        TextArea
    },

    mixins: [ CrudMixin ],

    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
        }
    }
};
</script>