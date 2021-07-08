<template>
    <div class="dropdown">
        <div class="btn-group">
            <span class="btn btn-light border">
                {{ count }} {{ label }} selected
            </span>
            <button
                class="btn btn-light border dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
            >
                Actions
            </button>
            <div
                class="dropdown-menu"
                style="overflow-x: auto; max-height: 150px;"
            >
                <button
                    v-for="(action, index) in actions"
                    :key="`action-item-${index}`"
                    type="button"
                    class="dropdown-item"
                    @click="confirm(action.event, action.confirmed, action.message, action.title)"
                >
                    {{ action.text }}
                </button>

                <action-button
                    v-if="archiveUrl"
                    class="dropdown-item text-danger"
                    color="btn-light"
                    label="Archive"
                    :action-url="archiveUrl"
                    confirm-dialog
                    :params="params"
                    :title="`Archive ${count} ${label}`"
                    :message="`Are you sure you want to archive ${count} ${label}?`"
                    @load="load"
                    @success="success"
                />

                <action-button
                    v-if="restoreUrl"
                    class="dropdown-item text-warning"
                    color="btn-light"
                    label="Restore"
                    :action-url="restoreUrl"
                    confirm-dialog
                    :params="params"
                    :title="`Restore ${count} ${label}`"
                    :message="`Are you sure you want to restore ${count} ${label}?`"
                    @load="load"
                    @success="success"
                />
            </div>
        </div>
    </div>
</template>

<script>
import ActionButton from '../buttons/ActionButton.vue';
import LoaderMixin from '../../mixins/loader.js';
import ConfirmProps from '../../mixins/confirm/props.js';

export default {
    name: 'action-list',

    components: {
        ActionButton
    },

    mixins: [ LoaderMixin, ConfirmProps ],

    props: {

        actions: {
            default: null,
            type: Array
        },

        count: {
            type: [ Number, String ],
            default: 0
        },

        params: {
            default: null,
            type: Object
        },

        archiveUrl: {
            default: null,
            type: String
        },

        restoreUrl: {
            default: null,
            type: String
        }
    },

    computed: {
        label() {
            return this.count > 1 ? 'items' : 'item';
        }
    },

    methods: {
        success() {
            this.$emit('success');
        },

        confirm(action, confirmed = false, body = null, title = null) {
            if (confirmed) {
                let message = {
                    title: title || this.title,
                    body: body || this.message
                };

                let options = {
                    loader: true,
                    okText: this.okText,
                    cancelText: this.cancelText,
                    animation: 'fade',
                    type: this.dialogType,
                    verification: this.verification,
                    verificationHelp: this.verificationHelp
                };

                this.$dialog.confirm(message, options)
                    .then((dialog) => {
                        dialog.loading(false);
                        dialog.close();
                        action();
                    }).catch(() => {

                    });
            } else {
                action();
            }
        }
    }
};
</script>