<template>
    <div
        ref="elem"
        class="modal fade"
        tabindex="0"
        role="dialog"
        aria-hidden="true"
    >
        <div
            class="modal-dialog"
            :class="sizeClass"
        >
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5
                        v-show="$slots['title']"
                        class="modal-title"
                    >
                        <slot name="title" />
                    </h5>
                    <button
                        type="button"
                        class="close"
                        aria-label="Close"
                        @click="close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div
                    v-show="$slots['body']"
                    class="modal-body"
                >
                    <slot name="body" />
                </div>

                <!-- Modal Footer -->
                <div
                    v-show="$slots['footer']"
                    class="modal-footer"
                >
                    <slot name="footer" />
                </div>

                <slot name="content" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'modal',

    model: {
        prop: 'value',
        event: 'change'
    },

    props: {
        small: {
            default: false,
            type: Boolean
        },

        backdrop: {
            default: true,
            type: [ Boolean, String ],
            validator: (value) => {
                return [ 'static', true, false ].indexOf(value) !== -1;
            }
        },

        value: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        elem: null
    }),

    computed: {
        sizeClass() {
            return this.small ? 'modal-sm' : '';
        }
    },

    watch: {
        value(value) {
            if (value) {
                this.open();
            } else {
                this.close();
            }
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            this.elem = $(this.$refs.elem);

            this.elem.on('hidden.bs.modal', () => {
                this.close();
                this.$emit('change', false);
            });
        },

        open() {
            this.elem.modal({
                show: true,
                backdrop: this.backdrop
            });
        },

        close() {
            this.elem.modal('hide');
            this.$emit('change', false);
        }
    }
};
</script>

<style>
.modal { overflow: auto !important; }
</style>