<template>
    <div>
        <action-button
            v-if="item.canApprove"
            :small="small"
            color="btn-success"
            :action-url="item.approveUrl"
            icon="fa fa-check"
            confirm-dialog
            :disabled="loading"
            title="Approve"
            :message="'Are you sure you want to approve Sample Item #' + item.id + '?'"
            @load="load"
            @success="fetch"
            @error="fetch"
        >
            Approve
        </action-button>

        <button
            v-if="item.canDeny"
            type="button"
            class="btn btn-danger"
            :class="small ? 'btn-sm' : ''"
            @click="open"
        >
            <i class="fas fa-times mr-2" /> Deny
        </button>

        <modal
            v-if="item.canDeny"
            v-model="showModal"
        >
            <template #title>
                Deny {{ item.name }}?
            </template>

            <template #content>
                <form-request
                    :submit-url="item.denyUrl"
                    confirm-dialog
                    sync-on-success
                    validate
                    reset-on-success
                    @load="load"
                    @success="success"
                >
                    <div class="modal-body text-left">
                        <div class="row">
                            <text-area
                                class="col-12"
                                label="Reason"
                                name="reason"
                                placeholder="Please enter your reason"
                            />
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
                            Deny
                        </button>
                    </div>
                </form-request>
            </template>
        </modal>
    </div>
</template>

<script>
import LoaderMixin from 'Mixins/loader.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import Modal from 'Components/dialogs/Modal.vue';
import TextArea from 'Components/inputs/TextArea.vue';
import FormRequest from 'Components/forms/FormRequest.vue';

export default {
    name: 'sample-item-buttons',

    components: {
        FormRequest,
        ActionButton,
        Modal,
        TextArea
    },

    mixins: [ LoaderMixin ],

    props: {
        item: {
            default: null,
            type: Object
        },

        small: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        showModal: false
    }),

    methods: {
        success() {
            this.close();
            this.fetch();
        },

        fetch() {
            this.$emit('fetch');
        },

        open() {
            this.showModal = true;
        },

        close() {
            this.showModal = false;
        }
    }
};
</script>