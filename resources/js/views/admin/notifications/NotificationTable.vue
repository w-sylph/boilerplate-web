<template>
    <card>
        <filter-box @refresh="fetch">
            <template #left>
                <date-range
                    @input="filter($event)"
                />
            </template>
            <template #right>
                <action-button
                    v-if="readAllUrl"
                    small
                    :action-url="readAllUrl"
                    color="btn-primary"
                    icon="fa fa-envelope-open"
                    title="Read Notifications"
                    message="Mark all notifications as read?"
                    :disabled="!array_count(notifications)"
                    @load="load"
                    @success="updateNotifications"
                >
                    Mark all as Read
                </action-button>
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <data-list
            ref="data-table"
            :headers="headers"
            :filters="filters"
            :fetch-url="fetchUrl"
            :no-action="noAction"
            :disabled="disabled"
            order-by="created_at"
            order-desc
            max-height="78vh"
            infinite-scroll
            @load="load"
            @fetch="init"
        >
            <template #body="{ items }">
                <div class="fluid-container pr-5">
                    <div
                        v-for="(item, index) in items"
                        :key="`item-${index}`"
                        class="row"
                    >
                        <!-- timeline item 1 left dot -->
                        <div class="col-auto text-center flex-column d-none d-sm-flex">
                            <div class="row h-50">
                                <div class="col">
                                    &nbsp;
                                </div>
                                <div class="col">
                                    &nbsp;
                                </div>
                            </div>
                            <h5 class="m-2">
                                <span
                                    class="badge badge-pill"
                                    :class="item.read_at ? 'bg-light' : 'bg-success'"
                                >&nbsp;</span>
                            </h5>
                            <div class="row h-50">
                                <div class="col">
                                    &nbsp;
                                </div>
                                <div class="col">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <!-- timeline item 1 event content -->
                        <div class="col py-2">
                            <div
                                class="card"
                                :class="item.read_at ? '' : 'border-success shadow'"
                            >
                                <div class="card-body">
                                    <div class="float-right text-muted">
                                        {{ item.created_at }}
                                    </div>
                                    <h4 class="card-title text-muted">
                                        {{ item.title }}
                                    </h4>
                                    <p class="card-text">
                                        {{ item.message }}
                                    </p>

                                    <div class="float-right">
                                        <view-button
                                            v-if="item.read_at"
                                            :href="item.showUrl"
                                            target="_blank"
                                        >
                                            Show Details
                                        </view-button>

                                        <action-button
                                            small
                                            :show-alt="item.read_at"
                                            :action-url="item.readUrl"
                                            :alt-action-url="item.unreadUrl"
                                            color="btn-primary"
                                            icon="fas fa-eye"
                                            alt-color="btn-secondary"
                                            alt-icon="fas fa-envelope"
                                            title="Read Notification"
                                            alt-title="Unread Notification"
                                            message="Mark notification as read?"
                                            alt-message="Mark notification as unread?"
                                            :href="item.read_at ? null : item.showUrl"
                                            target="_blank"
                                            :confirm-dialog="item.read_at"
                                            :hide-response="!item.read_at"
                                            :disabled="!item.showUrl && !item.read_at"
                                            @load="load"
                                            @success="updateNotifications"
                                            @error="updateNotifications"
                                        >
                                            {{ item.read_at ? 'Unread' : 'Show Details' }}
                                        </action-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </data-list>

        <loader :loading="loading" />
    </card>
</template>

<script>
import ListMixin from '../../../mixins/list.js';
import ArrayMixin from '../../../mixins/array.js';
import { bus } from '../../../bus.js';

import ViewButton from '../../../components/buttons/ViewButton.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DateRange from '../../../components/inputs/DateRange.vue';
import DataList from '../../../components/lists/DataList.vue';

export default {
    name: 'notification-table',

    components: {
        DataList,
        ViewButton,
        ActionButton,
        DateRange
    },

    mixins: [ ListMixin, ArrayMixin ],

    props: {
        readAllUrl: {
            default: null,
            type: String
        }
    },

    data: () => ({
        notifications: []
    }),

    computed: {
        headers() {
            return [
                { text: 'Title' },
                { text: 'Message' },
                { text: 'Received Date', value: 'created_at' }
            ];
        }
    },

    methods: {
        init(data) {
            this.notifications = data.notifications;
        },

        updateNotifications() {
            bus.$emit('update-notification-count');
            this.sync();
        }
    }
};
</script>