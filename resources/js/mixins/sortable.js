import { Sortable } from 'sortablejs';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

    props: {
        sortUrl: {
            default: null,
            type: String
        },

        sortable: {
            type: Boolean,
            default: false
        },

        dragabbleClass: {
            default: null,
            type: String
        },

        handleClass: {
            default: null,
            type: String
        }
    },

    data: () => ({
        sortableElem: null,
        willInsertAfter: null
    }),

    computed: {
        isSortable() {
            let result = this.sortUrl && this.sortable;
            return result;
        }
    },

    mounted() {
        this.setupSortable();
    },

    methods: {
        setupSortable() {
            if (this.isSortable && this.$refs.sortable) {

                let config = {
                    scroll: true, // Enable the plugin. Can be HTMLElement.
                    scrollSensitivity: 300, // px, how near the mouse must be to an edge to start scrolling.
                    scrollSpeed: 50, // px, speed of the scrolling
                    bubbleScroll: true, // apply autoscroll to all parent elements, allowing for easier movement
                    dragoverBubble: true,
                    animation: 150,
                    dataIdAttr: 'data-id',
                    disabled: this.disabled,
                    direction: 'horizontal',

                    onMove: (evt) => {
                        this.willInsertAfter = evt.willInsertAfter;
                    },

                    onUpdate: (evt) => {
                        let i = evt.newIndex;
                        let items = this.sortableElem.toArray();
                        let previous = items[i==0?items.length-1:i-1];
                        let current = items[i];
                        let next = items[i==items.length-1?0:i+1];

                        let params = {
                            current: current,
                            previous: previous,
                            next: next,
                            willInsertAfter: this.willInsertAfter
                        };

                        axios.post(this.sortUrl, params)
                        .then(() => {
                            // console.log(response);
                        }).catch(() => {
                            // console.log(error);
                        }).then(() => {

                        });
                    }
                };

                if (this.dragabbleClass) {
                    config.draggable = this.dragabbleClass;
                }

                if (this.handleClass) {
                    config.handle = this.handleClass;
                }

                this.sortableElem = new Sortable(this.$refs.sortable, config);

            }
        },

        enableSorting() {
            if (this.sortableElem) {
                this.sortableElem.option('disabled', false);
            }
        },

        disableSorting() {
            if (this.sortableElem) {
                this.sortableElem.option('disabled', true);
            }
        }
    }
};