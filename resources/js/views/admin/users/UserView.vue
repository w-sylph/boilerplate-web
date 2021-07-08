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
                    class="col-sm-12 col-md-6"
                    label="Email"
                    name="email"
                />

                <text-field
                    v-model="item.username"
                    class="col-sm-12 col-md-6"
                    label="Username"
                    name="username"
                />

                <file-picker
                    class="form-group col-sm-12 col-md-12 mt-2"
                    :value="item.path"
                    type="image"
                    label="Avatar"
                    name="file_path"
                    placeholder="Choose a File"
                />

                <birthday-picker
                    v-model="item.birthday"
                    class="col-sm-12"
                    label="Birthday"
                    name="birthday"
                />

                <gender-selector
                    v-model="item.gender"
                    class="col-12"
                    name="gender"
                    label="Gender"
                />

                <contact-number
                    v-model="item.mobile_number"
                    type="mobile"
                    label="Mobile Number"
                    name="mobile_number"
                    class="col-sm-12 col-md-6"
                />

                <contact-number
                    v-model="item.telephone_number"
                    type="telephone"
                    label="Telephone Number"
                    name="telephone_number"
                    class="col-sm-12 col-md-6"
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
                    :message="'Are you sure you want to archive User #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore User #' + item.id + '?'"
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
import FilePicker from '../../..//components/inputs/FilePicker.vue';
import BirthdayPicker from '../../../components/inputs/BirthdayPicker.vue';
import ContactNumber from '../../../components/inputs/ContactNumber.vue';
import GenderSelector from '../../../components/inputs/GenderSelector.vue';

export default {
    name: 'user-view',

    components: {
        ActionButton,
        TextField,
        FilePicker,
        BirthdayPicker,
        ContactNumber,
        GenderSelector
    },

    mixins: [ CrudMixin ],

    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
        }
    }
};
</script>