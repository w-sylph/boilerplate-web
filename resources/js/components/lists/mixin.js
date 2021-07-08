import { bus } from '../../bus.js';
import FetchMixin from '../../mixins/fetch.js';
import ArrayMixin from '../../mixins/array.js';
import SortableMixin from '../../mixins/sortable.js';

import Pagination from '../../components/paginations/Pagination.vue';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

    components: {
        Pagination
    },

    mixins: [ FetchMixin, ArrayMixin, SortableMixin ],

    props: {
        /* An array of objects that each describe a header column. See the example below for a definition of all properties */
        headers: {
            type: [ Array, null ],
            default: () => [
                // {
                //  text: string
                //  value: string
                //  sortable?: boolean
                //  class?: string | string[]
                //  width?: string | number
                //  sort?: string
                // }
            ]
        },

        /* Filter values */
        filters: {
            default: () => new Object,
            type: Object
        },

        /* Specify the max total visible pagination numbers */
        totalVisible: {
            default: 7,
            type: [ Number, String ]
        },

        /* Default order is descending */
        orderDesc: {
            default: false,
            type: Boolean
        },

        /* Default column to sort */
        orderBy: {
            default: null,
            type: String
        },

        /* Show select input field */
        showSelect: {
            default: false,
            type: Boolean
        },

        /* Text for empty list */
        emptyText: {
            default: 'No results found.',
            type: String
        },

        /* Visible item per page */
        perPage: {
            type: Number,
            default: 10
        },

        /* List of visible item per page */
        limits: {
            type: Array,
            default: () => [ 10, 15, 20 ]
        },

        /* Disable intial fetch */
        disabled: {
            default: false,
            type: Boolean
        },

        /* Hide action column */
        noAction: {
            default: false,
            type: Boolean
        },

        /* Action header text */
        actionText: {
            default: 'Action',
            type: String
        },

        infiniteScroll: {
            default: false,
            type: Boolean
        },

        endText: {
            default: 'End of Content',
            type: String
        },

        maxHeight: {
            default: 'auto',
            type: String
        }
    },

    data() {
        return {
            /* value of select input */
            selected: false,

            /* Current Page */
            page: 1,

            /* Visible items per page */
            limit: 10,

            /* list of items */
            items: [],

            /* pagination values */
            pagination: {},

            /* true asc, false desc */
            order: true,

            /* column to sort */
            orderColumn: null,

            isFetching: false,

            nopagination: null,

            params: {}
        };
    },

    computed: {
        /* Fetch parameters */
        fetchParams() {
            let filters = this.filters;

            let pagination = {
                page: this.page,
                order: this.order ? 'asc' : 'desc',
                orderBy: this.sortColumn,
                per_page: this.limit
            };

            if (this.nopagination) {
                filters.nopagination = 1;
            }

            let combined = Object.assign(filters, pagination);

            return combined;
        },

        /* Condition to allow intial fetching */
        canFetch() {
            return this.fetchUrl && !this.disabled && !this.hasFetched;
        },

        /* Column to order the list */
        sortColumn() {
            return this.orderColumn ? this.orderColumn : this.orderBy;
        },

        /* Colspan of table when list is empty */
        colspan() {
            let result = this.array_count(this.headers);
            /* add colspan when select is visable */
            if (this.showSelect) {
                result += 1;
            }

            /* add colspan when action is visible */
            if (!this.noAction) {
                result += 1;
            }

            return result;
        },

        isLastPage() {
            return this.pagination.current_page === this.pagination.last_page;
        }
    },

    watch: {
        /* fetch items when page is changed */
        page() {
            this.fetch();
        },

        /* return to page 1 when limit is changed to prevent empty pages */
        limit() {
            if (this.hasFetched) {
                if (this.page == 1) {
                    this.fetch();
                } else {
                    this.page = 1;
                }
            }
        },

        /* emit when checkbox value is changed */
        selected(value) {
            let ids = this.array_pluck(this.items, 'id');
            this.$emit('select', { value: value, params: this.fetchParams, ids: ids });
        },

        sortable(value) {
            this.initSortable(value);
        },

        hasFetched(value) {
            if (value) {
                this.$emit('has-fetched');
            }
        }
    },
    created() {
        /* list to events to allow initial fetching */
        bus.$on('sync-tables', () => {
            this.hasFetched = false;
        });

        /* Set initial visible items per page */
        this.limit = this.perPage ? parseInt(this.perPage) : this.limit;

        this.setupOrder();
    },

    mounted() {
        this.initSortable(this.sortable);
    },

    methods: {
        setupOrder() {
            /* Set initial order of items */
            this.order = !this.orderDesc;
            this.$emit('sort', this.order ? 'asc' : 'desc');

            /* Set initial column to sort */
            this.orderColumn = this.orderBy ? this.orderBy : this.orderColumn;
        },

        initSortable(value) {
            if (value) {
                this.orderColumn = 'order_column';
                this.order = true;
                this.nopagination = 1;
            } else {
                this.setupOrder();
                this.nopagination = null;
            }

            if (this.hasFetched) {
                this.fetch().then(() => {
                    if (!this.sortableElem) {
                        this.setupSortable();
                    }

                    if (value) {
                        this.enableSorting();
                    } else {
                        this.disableSorting();
                    }
                });
            }
        },

        /* fetch callback */
        fetchSuccess(data) {
            if (this.infiniteScroll && this.isFetching) {
                this.items = this.items.concat(data.items);

                this.isFetching = false;
            } else {
                this.items = data.items;
            }

            this.pagination = data.pagination ? data.pagination : {};
            this.$emit('select', {});
            this.$emit('fetch', data);
        },

        /* set order column and then fetch re-ordered items */
        sortBy(value) {
            if (this.sortable) {
                return;
            }

            if (!value) {
                return;
            }

            if (!this.array_count(this.items)) {
                return;
            }

            if (this.orderColumn == value) {
                this.order = !this.order;
            } else {
                this.order = this.orderDesc;
            }

            this.orderColumn = value;
            this.fetch();
            this.$emit('sort', this.order ? 'asc' : 'desc');
        },

        /* display whether icon is asc or desc */
        sortIcon(value) {
            if (this.orderColumn == value) {
                return this.order ? 'fas fa-sort-down' : 'fas fa-sort-up';
            }

            return 'fas fa-sort';
        },

        scroll(event) {
            if (this.infiniteScroll && !this.isFetching && !(this.isLastPage)) {

                let element = event.target;

                if (element.clientHeight + 50 > element.scrollHeight - element.scrollTop) {
                    this.isFetching = true;
                    this.page++;
                }
            }
        }
    }
};