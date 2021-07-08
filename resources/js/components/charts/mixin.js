// @vue/component

import { Chart } from 'chart.js';
import ArrayMixin from 'Mixins/array.js';

export default {
    mixins: [ ArrayMixin ],

    props: {
        items: {
            default: [],
            type: [ Array, Object ]
        },

        height: {
            default: 400,
            type: [ Number, String ]
        },

        width: {
            default: 400,
            type: [ Number, String ]
        },

        itemLabel: {
            default: 'label',
            type: String
        },

        itemData: {
            default: 'data',
            type: String
        },

        itemBgColor: {
            default: 'backgroundColor',
            type: String
        },

        label: {
            default: null,
            type: String
        },

        title: {
            default: null,
            type: String
        },

        fontSize: {
            default: 14
        },

        titlePosition: {
            default: 'bottom',
            type: String
        },

        type: {
            default: null,
            type: String
        },

        usePointStyle: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        elem: null
    }),

    watch: {
        items(value) {
            if (value && !this.elem) {
                this.initChart(value);
            } else if (value && this.elem) {
                this.elem.data = this.getData(value);
                this.elem.update();
            }
        }
    },

    methods: {
        initChart(array) {
            let ctx = this.$refs.elem.getContext('2d');

            let config = {
                type: this.type,
                data: this.getData(array),
                options: {
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: this.usePointStyle
                        }
                    },
                    title: {
                        display: true,
                        text: this.title,
                        position: this.titlePosition,
                        fontSize: this.fontSize
                    },
                    animation: {
                        duration: 0 // general animation time
                    },
                    hover: {
                        animationDuration: 0 // duration of animations when hovering an item
                    },
                    responsiveAnimationDuration: 0
                }
            };

            this.elem = new Chart(ctx, config);
        }
    }
};