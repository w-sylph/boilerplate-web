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
                <selector
                    :items="types"
                    placeholder="Filter by Status"
                    @input="filter($event, 'email_verified_at')"
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
                    <td>{{ item.gender }}</td>
                    <td>{{ item.birthday }}</td>
                    <td>{{ item.age }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.username }}</td>
                    <td>{{ formatTelephoneNumber(item.telephone_number) }}</td>
                    <td>{{ formatMobileNumber(item.mobile_number, item.mobile_number_country_code) }}</td>
                    <td>
                        <span
                            :class="item.status_class"
                            class="badge"
                        >{{ item.status }}</span>
                    </td>
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
                            :message="'Are you sure you want to archive User #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore User #' + item.id + '?'"
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
import NumberMixin from '../../../mixins/number.js';

import SearchForm from '../../../components/forms/SearchForm.vue';
import ExportForm from '../../../components/forms/ExportForm.vue';
import Selector from '../../../components/inputs/Selector.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';

export default {
    name: 'user-table',

    components: {
        'selector': Selector,
        'export-form': ExportForm,
        'search-form': SearchForm,
        'view-button': ViewButton,
        'action-button': ActionButton
    },

    mixins: [ ListMixin, NumberMixin ],

    props: {
        exportUrl: {
            default: null,
            type: String
        }
    },

    data: () => ({
        types: [
            { value: true, label: 'Verified' },
            { value: false, label: 'Unverified' }
        ]
    }),

    computed: {
        headers() {
            return [
                { text: '#', value: 'id' },
                { text: 'First Name', value: 'first_name' },
                { text: 'Last Name', value: 'last_name' },
                { text: 'Gender', value: 'gender' },
                { text: 'Birthday', value: 'birthday' },
                { text: 'Age', value: 'age' },
                { text: 'Email', value: 'email' },
                { text: 'Username', value: 'username' },
                { text: 'Telephone #', value: 'telephone_number' },
                { text: 'Mobile #', value: 'mobile_number' },
                { text: 'Status', value: 'status' },
                { text: 'Registration Date', value: 'created_at' }
            ];
        }
    }
};
</script>