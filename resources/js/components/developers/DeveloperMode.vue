<template>
    <div>
        <div
            class="float-right d-none d-sm-block"
        >
            <a
                href="#"
                class="d-inline text-white"
                @click.prevent="toggle"
            ><span class="font-weight-bold">Version</span> {{ label }}</a>
        </div>

        <modal v-model="showModal">
            <template #title>
                <span class="text-dark">
                    Developer Mode
                </span>
            </template>

            <template #content>
                <form-request
                    :submit-url="submitUrl"
                    confirm-dialog
                    sync-on-success
                    validate
                    reset-on-success
                    @load="load"
                >
                    <div class="modal-body text-left">
                        <div class="row text-dark">
                            <radio-list
                                v-model="guard"
                                class="col-12"
                                inline
                                :items="guards"
                                item-value="id"
                                item-text="name"
                                name="guard"
                            />

                            <selector
                                v-if="guard == 'admin'"
                                class="col-12"
                                label="Change Account (Admin)"
                                :items="admins"
                                item-text="email"
                                item-value="id"
                                placeholder="Select admin to change account"
                                name="user"
                            />

                            <selector
                                v-if="guard == 'web'"
                                class="col-12"
                                label="Change Account (User)"
                                :items="users"
                                item-text="email"
                                item-value="id"
                                placeholder="Select user to change account"
                                name="user"
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
                            Submit
                        </button>
                    </div>
                </form-request>
            </template>
        </modal>
    </div>
</template>

<script>
import FetchMixin from 'Mixins/fetch.js';
import LazyMixin from 'Mixins/lazy.js';

import Modal from 'Components/dialogs/Modal.vue';
import FormRequest from 'Components/forms/FormRequest.vue';
import Selector from 'Components/inputs/Selector.vue';
import RadioList from 'Components/inputs/RadioList.vue';

export default {
    name: 'developer-mode',

    components: {
        FormRequest,
        Modal,
        RadioList,
        Selector
    },

    mixins: [ FetchMixin, LazyMixin ],

    props: {
        label: {
            default: null,
            type: String
        },

        submitUrl: {
            default: null,
            type: String
        }
    },

    data: () => ({
        users: [],
        admins: [],

        guards: [
            { id: 'admin', name: 'Admin' },
            { id: 'web', name: 'Web' }
        ],

        guard: 'admin',

        clickNumber: 0,
        showModal: false
    }),

    methods: {
        fetchSuccess(data) {
            this.users = data.users;
            this.admins = data.admins;
        },

        toggle() {
            if (this.clickNumber < 3) {
                this.clickNumber++;
                return;
            } else {
                this.open();
            }
        },

        open() {
            this.showModal = true;
            this.fetchSetup();
        },

        close() {
            this.showModal = false;
        }
    }
};
</script>