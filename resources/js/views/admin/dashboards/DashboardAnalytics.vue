<template>
    <div class="row">
        <div class="col-12">
            <card>
                <template #header>
                    {{ title }}
                </template>

                <div class="row">
                    <div class="col-12">
                        <date-range
                            @input="filter($event)"
                        />
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="row">
                            <box-widget
                                class="col-sm-12 col-md-12"
                                label="System Usage"
                                color="bg-primary"
                                icon="fas fa-chart-line"
                                :count="usage"
                            />

                            <box-widget
                                class="col-sm-6 col-md-6"
                                label="Total Users"
                                color="bg-warning"
                                icon="fa fa-users"
                                :count="count"
                            />

                            <box-widget
                                class="col-sm-6 col-md-6"
                                label="Total Logins"
                                color="bg-success"
                                icon="fas fa-key"
                                :count="login"
                            />

                            <box-widget
                                class="col-sm-6 col-md-6"
                                label="Active Users"
                                color="bg-info"
                                icon="fas fa-user-check"
                                :count="active"
                            />

                            <box-widget
                                class="col-sm-6 col-md-6"
                                label="Inactive Users"
                                color="bg-danger"
                                icon="fas fa-user-alt-slash"
                                :count="inactive"
                            />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <pie-chart
                            :items="usage_chart"
                            type="doughnut"
                            title="System Usage"
                            use-point-style
                        />
                    </div>
                </div>
            </card>
        </div>

        <loader :loading="loading" />
    </div>
</template>

<script>
import FetchMixin from '../../../mixins/fetch.js';

import Card from '../../../components/containers/Card.vue';
import DateRange from '../../../components/inputs/DateRange.vue';
import PieChart from '../../../components/charts/PieChart.vue';
import BoxWidget from '../../../components/widgets/BoxWidget.vue';

export default {
    name: 'dashboard-analytics',

    components: {
        'card': Card,
        'date-range': DateRange,
        'pie-chart': PieChart,
        'box-widget': BoxWidget
    },

    mixins: [ FetchMixin ],

    props: {
        title: {
            default: null,
            type: String
        }
    },

    data: () => ({
        filters: {},

        active: 0,
        count: 0,
        inactive: 0,
        login: 0,
        usage: '0 %',
        usage_chart: []
    }),

    computed: {
        fetchParams() {
            return this.filters;
        }
    },

    methods: {
        filter(value) {
            this.filters = value;

            this.$nextTick(() => {
                this.fetch();
            });
        },

        fetchSetup() {
            if (!this.hasFetched) {
                this.fetch();
            }
        },

        fetchSuccess(data) {
            this.active = data.active;
            this.count = data.count;
            this.inactive = data.inactive;
            this.login = data.login;
            this.usage = data.usage;
            this.usage_chart = data.usage_chart;
        }
    }
};
</script>