<template>
    <div
        ref="modal"
        class="modal fade"
        tabindex="-1"
        role="dialog"
    >
        <div
            class="modal-dialog modal-dialog-centered"
            role="document"
        >
            <div class="modal-content">
                <div class="card theme--cardBg p-0 m-0">
                    <div class="card-header text-center theme--cardBg border-0 pb-0">
                        <h1 class="d-inline-block mt-2">
                            <dialog-icon
                                :type="dialog.type"
                            />
                        </h1>
                        <button
                            type="button"
                            class="float-right close"
                            @click="close"
                        >
                            <span aria-hidden="true theme--text">&times;</span>
                        </button>
                    </div>
                    <div
                        class="card-body pt-3 theme--text"
                        :class="messageOption.class"
                        v-html="message"
                    />
                    <div
                        v-if="array_count(buttons) > 0 || !options.hideCloseButton"
                        class="card-footer theme--cardBg border-0 text-center dialog--content"
                    >
                        <button
                            v-if="!options.hideCloseButton"
                            type="button"
                            class="btn btn-primary"
                            @click="close"
                        >
                            Continue
                        </button>

                        <template v-for="(button, index) in buttons">
                            <button
                                :key="`button-${index}`"
                                :type="button.type"
                                :class="button.class"
                                @click="submit(button)"
                            >
                                <span :class="button.icon" />
                                {{ button.label }}
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { bus } from '../../bus.js';
import ArrayMixin from '../../mixins/array.js';
import DialogIcon from './DialogIcon.vue';

export default {
    name: 'dialog-box',

    components: {
        DialogIcon
    },

    mixins: [ ArrayMixin ],

    data: () => ({
        dialog: {},

        title: null,
        message: null,
        type: null,

        buttons: [],

        options: {},
        messageOption: {},

        modal: null
    }),

    watch: {
        showModal(value) {
            if (! value) {
                bus.$emit('hide-dialog');
            }
        }
    },

    mounted() {
        this.modal = $(this.$refs.modal);
        bus.$on('show-dialog', (data) => {
            this.setup(data);
        });
    },

    methods: {
        /* Set icons, message and options then open modal */
        setup(data) {
            this.title = data.title;
            this.type = data.type;
            this.message = data.message;
            this.options = data.options ? data.options : {};

            if (!this.options.closeButtonLabel) {
                this.options.closeButtonLabel = 'Continue';
            }

            this.$nextTick(() => {
                this.setButtons(this.options.buttons);
                this.setMessageOptions(this.options.message);

                this.dialog = this.getDialogStyles(data.type);
            });

            this.$nextTick(() => {
                this.open();
            });
        },

        /* run action of response option buttons */
        submit(button) {
            return button.closes ? this.close() : button.action();
        },

        open() {
            this.modal.modal('show');
        },

        close() {
            this.modal.modal('hide');
        },

        /* create buttons from response with each properties */
        setButtons(buttons) {
            this.buttons = [];

            if (this.array_count(buttons) > 0) {
                buttons.forEach(button => {
                    button.type = button.type ? button.type : 'button';
                    button.label = button.label ? button.label : '';
                    button.icon = button.icon ? button.icon : '';
                    button.class = button.class ? button.class : '';
                    button.action = button.action ? button.action : () => {};
                    button.closes = button.closes ? button.closes : false;

                    this.buttons.push(button);
                });
            }
        },

        /* modify message with respons option */
        setMessageOptions(messageOption) {
            messageOption = messageOption ? messageOption : {};
            this.messageOption.class = messageOption.class;
        },

        getDialogStyles(type) {
            let result = {};

            switch (type) {
                case 'error':
                    result = {
                        class: 'text-white bg-danger',
                        icon: 'fas fa-times',
                        type: 'error'
                    };
                    break;
                case 'warning':
                    result = {
                        class: 'text-white bg-warning',
                        icon: 'fas fa-exclamation-triangle',
                        type: 'warning'
                    };
                    break;
                case 'success':
                    result = {
                        class: 'text-white bg-success',
                        icon: 'fas fa-check-circle',
                        type: 'success'
                    };
                    break;
                case 'info':
                    result = {
                        class: 'text-white bg-info',
                        icon: 'fas fa-question-circle',
                        type: 'info'
                    };
                    break;
            }

            return result;
        }
    }
};
</script>