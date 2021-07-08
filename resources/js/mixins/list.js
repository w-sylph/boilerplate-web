import { bus } from '../bus.js';
import LoaderMixin from './loader.js';

import Card from '../components/containers/Card.vue';
import Loader from '../components/loaders/Loader.vue';
import DataTable from '../components/lists/DataTable.vue';
import FilterBox from '../components/containers/FilterBox.vue';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

    components: {
        'card': Card,
        'loader': Loader,
        'data-table': DataTable,
        'filter-box': FilterBox
    },

    mixins: [ LoaderMixin ],

    props: {
        fetchUrl: {
            default: null,
            type: String
        },

        disabled: {
            default: false,
            type: Boolean
        },

        noAction: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        filters: {}
    }),

    computed: {
        /* headers object for list table */
        headers() {
            return [
                //
            ];
        }
    },

    methods: {
        /* Add filter to filter object and then fetch */
        filter(event, column = null, fetch = true) {
            if (fetch) {
                if (!this.$refs['data-table'].hasFetched) {
                    return;
                }
            }

            let data = {};

            if (column) {
                data[column] = event;
            } else {
                data = event;
            }

            this.filters = Object.assign(this.filters, data);

            if (fetch) {
                this.fetch();
            }
        },

        /* Call fetch on method on component */
        fetch() {
            this.selected_ids = [];
            this.$refs['data-table'].fetch();
        },

        /* Initial fetch with conditional */
        fetchSetup() {
            if (!this.$refs['data-table'].hasFetched) {
                this.fetch();
            }
        },

        fetchSync() {
            if (this.$refs['data-table'].hasFetched) {
                this.fetch();
            }
        },

        /* Fire event that handle fetching of all visible components */
        sync() {
            bus.$emit('sync-tables');
            this.fetch();
        }
    }
};