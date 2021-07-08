<template>
    <card>
        <filter-box @refresh="fetch">
            <template #left />
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
            order-by="id"
            @load="load"
        >
            <template #body="{ items }">
                <tr
                    v-for="(item, index) in items"
                    :key="`item-${index}`"
                >
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
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
                            :message="'Are you sure you want to archive Page #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore Page #' + item.id + '?'"
                            @load="load"
                            @success="sync"
                        />
                    </td>
                </tr>
            </template>
        </data-table>

        <loader :loading="loading" />
    </card>
</template>

<script>
import ListMixin from '../../../mixins/list.js';

import SearchForm from '../../../components/forms/SearchForm.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';

export default {
    name: 'page-table',

    components: {
        SearchForm,
        ViewButton,
        ActionButton
    },

    mixins: [ ListMixin ],

    computed: {
        headers() {
            return [
                { text: '#', value: 'id' },
                { text: 'Name', value: 'name' },
                { text: 'Created Date', value: 'created_at' }
            ];
        }
    }
};
</script>