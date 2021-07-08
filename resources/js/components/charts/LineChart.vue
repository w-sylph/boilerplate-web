<template>
    <div>
        <canvas
            ref="elem"
            :width="width"
            :height="height"
        />
    </div>
</template>

<script>
import ChartMixin from './mixin.js';

export default {
    name: 'line-chart',
    mixins: [ ChartMixin ],

    props: {
        /* chart type */
        type: {
            default: 'line',
            type: String
        }
    },

    methods: {
        /* Format array for line chart */
        getData(array) {
            let data = array.datasets;
            let datasets = [];

            data.forEach((data) => {
                datasets.push({
                    label: data.datasets.label,
                    data: data.datasets.data,
                    fill: false,
                    borderColor: data.datasets.borderColor, // Add custom color border (Line)
                    backgroundColor: data.datasets.backgroundColor, // Add custom color background (Points and Fill)
                    borderWidth: 1
                });
            });

            return {
                labels: array.labels,
                datasets: datasets
            };
        }
    }
};
</script>