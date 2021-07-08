<template>
    <button
        type="button"
        :class="[ buttonColor, buttonSize, buttonFlat ]"
        :disabled="buttonDisabled"
        @click="showConfirm"
    >
        <template v-if="buttonLoading">
            <div class="spinner-border spinner-border-sm">
                <span class="sr-only">Loading...</span>
            </div>
        </template>
        <template v-else>
            <i
                v-if="iconVisibility"
                :class="[ buttonIcon ]"
            />
            <slot>{{ buttonLabel }}</slot>
        </template>
    </button>
</template>

<script>
/* Read Vue Dialog for usage and options https://www.npmjs.com/package/vuejs-dialog */
import ResponseMixin from 'Mixins/response.js';
import ConfirmProps from 'Mixins/confirm/props.js';
import ConfirmMethods from 'Mixins/confirm/methods.js';

export default {
    name: 'action-button',

    mixins: [ ResponseMixin, ConfirmProps, ConfirmMethods ],

    model: {
        props: 'showAlt',
        event: 'change'
    },

    props: {
        /**
         * States
         */

        disabled: {
            default: false,
            type: Boolean
        },

        loading: {
            default: false,
            type: Boolean
        },

        showAlt: {
            default: false,
            type: [ Boolean, Number, String ]
        },

        /**
         * Button Label & Styling
         */

        icon: {
            default: null,
            type: String
        },

        altIcon: {
            default: null,
            type: String
        },

        label: {
            default: null,
            type: String
        },

        altLabel: {
            default: null,
            type: String
        },

        color: {
            default: null,
            type: String
        },

        altColor: {
            default: null,
            type: String
        },

        /**
         * Link
         */
        href: {
            default: null,
            type: String
        },

        target: {
            default: null,
            type: String
        },

        /**
         * Buttton Sizes
         */

        small: {
            default: false,
            type: Boolean
        },

        /**
         * Actions
         */

        actionUrl: {
            default: null,
            type: String
        },

        altActionUrl: {
            default: null,
            type: String
        },

        hideResponse: {
            default: false,
            type: Boolean
        },

        /**
         * Confirm Dialog
         */
        altTitle: {
            default: 'Confirm Action',
            type: String
        },

        altMessage: {
            default: 'Are you sure you want to proceed with this action?',
            type: String
        },

        flat: {
            default: false,
            type: Boolean
        },

        options: {
            default: () => new Object,
            type: Object
        },

        /**
         * Params
         */

        params: {
            default: null,
            type: Object
        }
    },

    data: () => ({
        isLoading: false
    }),

    computed: {
        buttonDisabled() {
            return this.disabled;
        },

        buttonLoading() {
            return this.loading;
        },

        buttonColor() {
            return this.showAlt ? this.altColor : this.color;
        },

        buttonSize() {
            let result = null;

            if (this.small) {
                result = 'btn-sm';
            }

            return result;
        },

        buttonFlat() {
            return this.flat ? '' : 'btn';
        },

        buttonLabel() {
            return this.showAlt ? this.altLabel : this.label;
        },

        buttonIcon() {
            return this.showAlt ? this.altIcon : this.icon;
        },

        iconVisibility() {
            return this.icon || this.altIcon;
        },

        dialogTitle() {
            return this.showAlt ? this.altTitle : this.title;
        },

        dialogMessage() {
            return this.showAlt ? this.altMessage : this.message;
        }
    },

    methods: {
        onDialogSuccess(event, dialog) {
            this.submit(event, dialog);
        },

        submit(event, dialog = null) {
            if (this.isLoading) {
                if (dialog) {
                    dialog.loading(false);
                    dialog.close();
                }
                return;
            }

            let url = this.showAlt ? this.altActionUrl : this.actionUrl;

            /* If no action url proceed to success immediately */
            if (!url) {
                if (dialog) {
                    dialog.loading(false);
                    dialog.close();
                }
                this.onSuccess();
                return;
            }

            this.load(true);

            axios.post(url, this.params)
                .then(response => {
                    const data = response.data;

                    if (!this.hideResponse && data.message) {
                        this.parseSuccess(data.message, null, this.options.success);
                    }

                    this.onSuccess(response);

                }).catch(error => {
                    if (!this.hideResponse) {
                        this.parseError(error, null, this.options.error);
                    }

                    this.onError(error);
                }).then(() => {

                    this.load(false);

                    if (dialog) {
                        dialog.loading(false);
                        dialog.close();
                    }
                });
        },

        onSuccess(response = null) {
            if (this.href) {
                if (this.target) {
                    window.open(this.href, this.target);
                } else {
                    window.location.href = this.href;
                }
            }

            this.$emit('success', response);
        },

        onError(error = null) {
            this.$emit('error', error);
        },

        load(value) {
            this.isLoading = value;
            this.$emit('load', value);
        }
    }
};
</script>