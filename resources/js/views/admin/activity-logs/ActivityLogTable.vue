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
                    :items="filterTypes"
                    placeholder="Filter by type"
                    no-search
                    @input="filter($event, 'subject_type')"
                />

                <selector
                    v-if="filterCausers"
                    class="ml-2"
                    :items="filterCausers"
                    placeholder="Filter by causer"
                    no-search
                    @input="filter($event, 'causer_type')"
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
                    <td v-if="showSubject">
                        <span class="badge badge-secondary">{{ item.subject_type }}</span>
                        <p v-if="item.subject_name">
                            {{ item.subject_name }}
                        </p>
                    </td>
                    <td>{{ item.name }}</td>
                    <td v-if="!hideCauser">
                        <a
                            :href="item.show_causer"
                            class="btn btn-link"
                            :class="!item.show_causer ? 'disabled' : ''"
                            target="_blank"
                        >
                            {{ item.caused_by }}
                        </a>
                    </td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="!noAction">
                        <view-button :href="item.showUrl" />
                    </td>
                </tr>
            </template>
        </data-table>

        <loader :loading="loading" />
    </card>
</template>

<script>
import ListMixin from '../../../mixins/list.js';

import Card from '../../../components/containers/Card.vue';
import SearchForm from '../../../components/forms/SearchForm.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';
import DateRange from '../../../components/inputs/DateRange.vue';
import Selector from '../../../components/inputs/Selector.vue';

export default {
    name: 'activity-log-table',

    components: {
        Card,
        SearchForm,
        ViewButton,
        DateRange,
        Selector
    },

    mixins: [ ListMixin ],

    props: {
        filterTypes: {
            default: null,
            type: Array
        },

        filterCausers: {
            default: null,
            type: Array
        },

        hideCauser: {
            default: false,
            type: Boolean
        },

        showSubject: {
            default: false,
            type: Boolean
        }
    },

    computed: {
        headers() {
            let array = [
                { text: '#', value: 'id' }
            ];

            if (this.showSubject) {
                array.push({ text: 'Subject', value: 'subject_type' });
            }

            array.push({ text: 'Description', value: 'description' });

            if (!this.hideCauser) {
                array.push({ text: 'Caused By', value: 'causer_type' });
            }

            array.push({ text: 'Created Date', value: 'created_at' });

            return array;
        }
    }
};
</script>