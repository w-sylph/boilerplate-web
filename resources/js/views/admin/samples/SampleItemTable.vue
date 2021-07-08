<template>
    <card>
        <div class="row mb-2">
            <div class="col-12">
                <export-form
                    v-if="exportUrl"
                    :submit-url="exportUrl"
                    :params="filters"
                />
            </div>
        </div>

        <filter-box @refresh="fetch">
            <template #left>
                <date-range
                    class="mr-2"
                    @input="filter($event)"
                />

                <selector
                    v-if="filterStatus"
                    :items="filterStatus"
                    placeholder="Filter by status"
                    @input="filter($event, 'status')"
                />
            </template>
            <template #right>
                <search-form
                    @search="filter($event, 'search')"
                />
            </template>
        </filter-box>

        <filter-box hide-refresh>
            <template #left>
                <action-list
                    v-show="array_count(selected_ids)"
                    :count="array_count(selected_ids)"
                    :archive-url="archiveUrl"
                    :restore-url="restoreUrl"
                    :params="{ ids: selected_ids }"
                    @load="load"
                    @success="sync"
                />
            </template>
            <template #right>
                <switcher
                    v-if="sortUrl"
                    v-model="sortable"
                    label="Re-order"
                />
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <data-table
            ref="data-table"
            :headers="headers"
            :filters="filters"
            :fetch-url="fetchUrl"
            :no-action="noAction"
            :disabled="disabled"
            order-by="created_at"
            order-desc
            show-select
            :sortable="sortable"
            :sort-url="sortUrl"
            @select="setItemIds"
            @load="load"
        >
            <template #body="{ items }">
                <template v-for="(item, index) in items">
                    <tr
                        :key="`item-${index}`"
                        :data-id="item.id"
                    >
                        <td>
                            <checkbox
                                v-model="selected_ids"
                                class="w-100 p-0 m-0"
                                name="selected_ids[]"
                                :value="item.id"
                            />
                        </td>
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>
                            <span
                                class="badge"
                                :class="item.status_class"
                            >{{ item.status }}</span>
                        </td>
                        <td>{{ item.created_at }}</td>
                        <td v-if="!noAction">
                            <div class="mb-2">
                                <view-button :href="item.showUrl" />

                                <action-button
                                    small
                                    color="btn-danger"
                                    alt-color="btn-warning"
                                    :show-alt="item.trashed"
                                    :action-url="item.archiveUrl"
                                    :alt-action-url="item.restoreUrl"
                                    icon="fas fa-trash"
                                    alt-icon="fas fa-trash-restore-alt"
                                    confirm-dialog
                                    :disabled="loading"
                                    title="Archive item"
                                    alt-title="Restore Iiem"
                                    :message="'Are you sure you want to archive ' + item.name + '?'"
                                    :alt-message="'Are you sure you want to restore ' + item.name + '?'"
                                    @load="load"
                                    @success="sync"
                                />
                            </div>

                            <sample-item-buttons
                                v-if="!item.trashed"
                                :item="item"
                                small
                                @load="load"
                                @fetch="update"
                            />
                        </td>
                    </tr>
                </template>
            </template>
        </data-table>

        <loader
            :loading="loading"
        />
    </card>
</template>

<script>
import ListMixin from 'Mixins/list.js';
import SelectableMixin from 'Mixins/selectable.js';
import { bus } from 'App/bus.js';

import ExportForm from 'Components/forms/ExportForm.vue';

import ActionList from 'Components/lists/ActionList.vue';
import SearchForm from 'Components/forms/SearchForm.vue';
import Checkbox from 'Components/inputs/Checkbox.vue';
import DateRange from 'Components/inputs/DateRange.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import Selector from 'Components/inputs/Selector.vue';
import Switcher from 'Components/inputs/Switcher.vue';
import SampleItemButtons from './SampleItemButtons.vue';

export default {
    name: 'sample-item-table',

    components: {
        ExportForm,
        ActionList,
        SearchForm,
        ViewButton,
        ActionButton,
        DateRange,
        Selector,
        Switcher,
        Checkbox,
        SampleItemButtons
    },

    mixins: [ ListMixin, SelectableMixin ],

    props: {
        filterStatus: {
            default: null,
            type: Array
        },

        exportUrl: {
            default: null,
            type: String
        },

        sortUrl: {
            default: null,
            type: String
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

    data: () => ({
        sortable: false
    }),

    computed: {
        headers() {
            return [
                { text: '#', value: 'id' },
                { text: 'Name', value: 'name' },
                { text: 'Status', value: 'status' },
                { text: 'Created Date', value: 'created_at' }
            ];
        }
    },

    mounted() {
        // this.listen();
    },

    methods: {
        update() {
            bus.$emit('update-sample-item-count');
            this.sync();
        },

        listen() {
            if (process.env.MIX_PUSHER_APP_ENABLED) {

                // Echo.channel('admin')
                // .listen('.sample.changed', (data) => {
                //     this.update();
                // });
            }
        }
    }
};
</script>