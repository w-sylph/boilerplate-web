<template>
    <form-request
        :submit-url="submitUrl"
        reset-on-success
        confirm-dialog
        sync-on-success
        @load="load"
    >
        <card>
            <template #header>
                Change Password
            </template>

            <div class="row">
                <password
                    class="col-sm-12"
                    label="Current Password"
                    name="current_password"
                    placeholder="Current Password"
                />
            </div>

            <div class="row">
                <password
                    class="col-sm-12 col-md-6"
                    label="New Password"
                    name="new_password"
                    placeholder="New Password"
                />

                <password
                    class="col-sm-12 col-md-6"
                    label="Confirm New Password"
                    name="new_password_confirmation"
                    placeholder="Confirm New Password"
                />
            </div>

            <template #footer>
                <action-button
                    type="submit"
                    :loading="loading"
                    :disabled="loading"
                    class="btn-primary"
                >
                    Change Password
                </action-button>
            </template>
        </card>
    </form-request>
</template>

<script>
import CrudMixin from '../../mixins/crud.js';
import Password from '../inputs/Password.vue';

export default {
    name: 'change-password-form',

    components: {
        Password
    },

    mixins: [ CrudMixin ]
};
</script>