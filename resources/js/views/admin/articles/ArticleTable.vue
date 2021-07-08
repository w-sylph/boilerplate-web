<template>
    <card>
        <filter-box @refresh="fetch">
            <template #left>
                <date-range
                    class="mr-2"
                    @input="filter($event)"
                />

                <selector
                    v-if="filterTypes"
                    class="mt-2"
                    :items="filterTypes"
                    placeholder="Filter by type"
                    @input="filter($event, 'type')"
                />
            </template>
            <template #right>
                <search-form
                    @search="filter($event, 'search')"
                />
            </template>
        </filter-box>

        <filter-box hide-refresh>
            <template #left />
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
            :sortable="sortable"
            :sort-url="sortUrl"
            @load="load"
        >
            <template #body="{ items }">
                <tr
                    v-for="(item, index) in items"
                    :key="`item-${index}`"
                    :data-id="item.id"
                >
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.published_at }}</td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="!noAction">
                        <div class="mb-2">
                            <view-button :href="item.showUrl" />

                            <action-button
                                small
                                color="btn-danger"
                                alt-color="btn-warning"
                                :show-alt="item.deleted_at"
                                :action-url="item.archiveUrl"
                                :alt-action-url="item.restoreUrl"
                                icon="fas fa-trash"
                                alt-icon="fas fa-trash-restore-alt"
                                confirm-dialog
                                :disabled="loading"
                                title="Archive Item"
                                alt-title="Restore Item"
                                :message="`Are you sure you want to archive ${item.name}?`"
                                :alt-message="`Are you sure you want to restore ${item.name}?`"
                                @load="load"
                                @success="sync"
                            />
                        </div>
                    </td>
                </tr>
            </template>
        </data-table>

        <loader
            :loading="loading"
        />
    </card>
</template>

<script>
import ListMixin from '../../../mixins/list.js';

import SearchForm from '../../../components/forms/SearchForm.vue';
import DateRange from '../../../components/inputs/DateRange.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';
import Selector from '../../../components/inputs/Selector.vue';
import Switcher from '../../../components/inputs/Switcher.vue';

export default {
    name: 'article-table',

    components: {
        SearchForm,
        ViewButton,
        ActionButton,
        DateRange,
        Selector,
        Switcher
    },

    mixins: [ ListMixin ],

    props: {
        filterTypes: {
            default: null,
            type: Array
        },

        sortUrl: {
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
                { text: 'Published At', value: 'published_at' },
                { text: 'Created Date', value: 'created_at' }
            ];
        }
    }
};
</script>