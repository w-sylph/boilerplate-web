<template>
    <card>
        <filter-box @refresh="fetch">
            <template #left>
                <date-range
                    class="mr-2"
                    @input="filter($event)"
                />

                <selector
                    :items="filterRoles"
                    item-value="id"
                    item-text="name"
                    placeholder="Filter by role"
                    no-search
                    @input="filter($event, 'role_id')"
                />
            </template>
            <template #right>
                <search-form
                    @search="filter($event, 'search')"
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
            @load="load"
        >
            <template #body="{ items }">
                <tr
                    v-for="(item, index) in items"
                    :key="`item-${index}`"
                >
                    <td>{{ item.id }}</td>
                    <td>{{ item.first_name }}</td>
                    <td>{{ item.last_name }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.roles }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>
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
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';
import Selector from '../../../components/inputs/Selector.vue';
import DateRange from '../../../components/inputs/DateRange.vue';

export default {
    name: 'admin-table',

    components: {
        SearchForm,
        ViewButton,
        ActionButton,
        Selector,
        DateRange
    },

    mixins: [ ListMixin ],

    props: {
        filterRoles: {
            default: null,
            type: Array
        }
    },

    computed: {
        headers() {
            return [
                { text: '#', value: 'id' },
                { text: 'First Name', value: 'first_name' },
                { text: 'Last Name', value: 'last_name' },
                { text: 'Email', value: 'email' },
                { text: 'Roles' },
                { text: 'Created', value: 'created_at' }
            ];
        }
    }
};
</script>