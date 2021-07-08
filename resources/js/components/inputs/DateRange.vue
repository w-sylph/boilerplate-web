<template>
    <div class="form-group">
        <div class="input-group">
            <input
                v-model="startDate"
                type="hidden"
                name="start_date"
            >
            <input
                v-model="endDate"
                type="hidden"
                name="end_date"
            >
            <button
                ref="elem"
                type="button"
                class="btn btn-light border-bottom-right-radius-0 border-top-right-radius-0 border d-inline-block"
            >
                <i class="fa fa-calendar mr-2" />
                <span>{{ dateLabel }}</span>
                <i class="fa fa-caret-down ml-2" />
            </button>
            <span
                class="btn btn-light border border-bottom-left-radius-0 border-top-left-radius-0 d-inline-block"
                @click="clear"
            >
                <i class="fa fa-times" />
            </span>
        </div>
    </div>
</template>

<script>
import 'bootstrap-daterangepicker';
import 'bootstrap-daterangepicker/daterangepicker.css';

export default {
    name: 'date-picker',

    props: {
        name: {
            default: null,
            type: String
        }
    },

    data: () => ({
        elem: null,
        startDate: null,
        endDate: null,

        startDisplay: null,
        endDisplay: null
    }),

    computed: {
        dateLabel() {
            let result = 'Select date here...';

            if (this.startDate && this.endDate) {
                if (this.startDisplay == this.endDisplay) {
                    result = this.startDisplay || this.endDisplay;
                } else {
                    result = `${this.startDisplay} - ${this.endDisplay}`;
                }
            }

            return result;
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            /* eslint-disable-next-line no-undef */
            let daterange = $(this.$refs.elem);

            this.elem = daterange.daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                opens: 'right',
                ranges: {
                    'Today': [ moment().startOf('day').format('YYYY-MM-DD'), moment().endOf('day').format('YYYY-MM-DD') ],
                    'Yesterday': [ moment().startOf('day').subtract(1, 'day').format('YYYY-MM-DD'), moment().endOf('day').subtract(1, 'day').format('YYYY-MM-DD') ],
                    'Last 7 days': [ moment().subtract(8, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'day').format('YYYY-MM-DD') ],
                    'Last 30 days': [ moment().subtract(31, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'day').format('YYYY-MM-DD') ],
                    'Last 60 days': [ moment().subtract(61, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'day').format('YYYY-MM-DD') ],
                    'Last 90 days': [ moment().subtract(91, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'day').format('YYYY-MM-DD') ],
                    'Last Month': [ moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'), moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD') ],
                    'Last Year': [ moment().subtract(1, 'year').startOf('year').format('YYYY-MM-DD'), moment().subtract(1, 'year').endOf('year').format('YYYY-MM-DD') ],
                    'This Month': [ moment().startOf('month').format('YYYY-MM-DD'), moment().endOf('month').format('YYYY-MM-DD') ],
                    'This Year': [ moment().startOf('year').format('YYYY-MM-DD'), moment().endOf('year').format('YYYY-MM-DD') ],
                    '1st Quarter': [ moment().set('month', 0).startOf('quarter').format('YYYY-MM-DD'), moment().set('month', 0).endOf('quarter').format('YYYY-MM-DD') ],
                    '2nd Quarter': [ moment().set('month', 3).startOf('quarter').format('YYYY-MM-DD'), moment().set('month', 3).endOf('quarter').format('YYYY-MM-DD') ],
                    '3rd Quarter': [ moment().set('month', 6).startOf('quarter').format('YYYY-MM-DD'), moment().set('month', 6).endOf('quarter').format('YYYY-MM-DD') ],
                    '4th Quarter': [ moment().set('month', 9).startOf('quarter').format('YYYY-MM-DD'), moment().set('month', 9).endOf('quarter').format('YYYY-MM-DD') ]
                }
            }, this.update);
        },

        update(startDate, endDate) {
            this.startDate = moment(startDate).format('YYYY-MM-DD');
            this.endDate = moment(endDate).format('YYYY-MM-DD');

            this.startDisplay = moment(startDate).format('MMMM D, YYYY');
            this.endDisplay = moment(endDate).format('MMMM D, YYYY');

            this.inputChange();
        },

        inputChange() {
            let result = {
                start_date: this.startDate,
                end_date: this.endDate
            };

            this.$emit('input', result);
        },

        clear() {
            this.startDate = null;
            this.endDate = null;

            this.inputChange();
        }
    }
};
</script>

<style scoped>
.btn {
	cursor: pointer;
}

li[data-range-key="Now"] {
    display: none;
}

.border-top-left-radius-0 {
	border-top-left-radius: 0px;
}

.border-top-right-radius-0 {
	border-top-right-radius: 0px;
}

.border-bottom-left-radius-0 {
	border-bottom-left-radius: 0px;
}

.border-bottom-right-radius-0 {
	border-bottom-right-radius: 0px;
}
</style>