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
    name: 'pie-chart',
    mixins: [ ChartMixin ],

    props: {
        /* chart type */
        type: {
            default: 'pie',
            type: String
        }
    },

    methods: {
        /* Format array for chart dataset */
        getData(array) {
            return {
                labels: this.array_pluck(array, this.itemLabel),
                datasets: [ {
                    label: this.label,
                    data: this.array_pluck(array, this.itemData),
                    backgroundColor: this.array_pluck(array, this.itemBgColor),
                    borderColor: '#ddd',
                    borderWidth: 1
                } ]
            };
        }
    }
};
</script>