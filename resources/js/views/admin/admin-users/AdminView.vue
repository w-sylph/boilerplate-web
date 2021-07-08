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
                    v-model="item.first_name"
                    class="col-sm-12 col-md-6"
                    label="First Name"
                    name="first_name"
                />

                <text-field
                    v-model="item.last_name"
                    class="col-sm-12 col-md-6"
                    label="Last Name"
                    name="last_name"
                />

                <text-field
                    v-model="item.email"
                    class="col-sm-12"
                    label="Email"
                    name="email"
                />

                <selector
                    v-if="editable"
                    v-model="roleIds"
                    class="col col-sm-12"
                    name="role_ids[]"
                    label="Roles"
                    :items="roles"
                    item-value="id"
                    item-text="name"
                    empty-text="None"
                    placeholder="Please select a role"
                    multiple
                />

                <file-picker
                    class="form-group col-sm-12 col-md-12 mt-2"
                    :value="item.path"
                    type="image"
                    label="Avatar"
                    name="file_path"
                    placeholder="Choose a File"
                />
            </div>

            <template #footer>
                <action-button
                    type="submit"
                    :disabled="loading"
                    class="btn-primary"
                >
                    Save Changes
                </action-button>

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
                    :message="'Are you sure you want to archive Role #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore Role #' + item.id + '?'"
                    :disabled="loading"
                    @load="load"
                    @success="fetch"
                />
            </template>
        </card>

        <loader :loading="loading" />
    </form-request>
</template>

<script>
import CrudMixin from '../../../mixins/crud.js';

import ActionButton from '../../../components/buttons/ActionButton.vue';
import TextField from '../../../components/inputs/TextField.vue';
import Selector from '../../../components/inputs/Selector.vue';
import FilePicker from '../../..//components/inputs/FilePicker.vue';

export default {
    name: 'admin-view',

    components: {
        TextField,
        ActionButton,
        Selector,
        FilePicker
    },

    mixins: [ CrudMixin ],

    props: {
        editable: {
            default: true,
            type: [ Boolean, String ]
        }
    },

    data: () => ({
        roles: [],
        roleIds: []
    }),

    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
            this.roles = data.roles ? data.roles : this.roles;
            this.roleIds = data.roleIds ? data.roleIds : this.roleIds;
        }
    }
};
</script>