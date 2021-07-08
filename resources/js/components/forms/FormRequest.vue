<template>
    <form
        method="GET"
        action="javascript:void(0)"
        @submit.prevent="showConfirm"
    >
        <input
            v-if="token"
            v-model="token"
            type="hidden"
            name="_token"
        >

        <slot />
    </form>
</template>

<script>
import { bus } from '../../bus.js';
import ArrayMixin from '../../mixins/array.js';
import ErrorMixin from 'Mixins/error.js';

import ResponseMixin from '../../mixins/response.js';
import UrlMixin from '../../mixins/url.js';
import LoaderMixin from '../loaders/mixin.js';
import ConfirmProps from '../../mixins/confirm/props.js';
import ConfirmMethods from '../../mixins/confirm/methods.js';

export default {
    name: 'form-request',

    mixins: [ ArrayMixin, ErrorMixin, ResponseMixin, LoaderMixin, ConfirmProps, ConfirmMethods, UrlMixin ],

    props: {
        /* Action URL */
        submitUrl: {
            default: null,
            type: String
        },

        /* Pass an object as the parameter */
        params: {
            default: null,
            type: Object
        },

        options: {
            default: () => new Object,
            type: Object
        },

        validate: {
            default: false,
            type: Boolean
        },

        disableSuccessResponse: {
            type: Boolean,
            default: false
        },

        paramsToUrl: {
            default: false,
            type: Boolean
        },

        /* Non-ajax form submit */
        submitOnSuccess: {
            default: false,
            type: Boolean
        },

        /* Reset form on success */
        resetOnSuccess: {
            default: false,
            type: Boolean
        },

        /* Fire Emitter to Sync other data tables */
        syncOnSuccess: {
            default: false,
            type: Boolean
        },

        disableRedirect: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        token: null,

        inputName: [
            'text-field', 'number-field', 'text-editor', 'radio-list', 'contact-number', 'zip-code',
            'selector', 'checkbox',
            'file-picker', 'date-picker', 'birthday-picker', 'option-picker',
            'password'
        ],

        inputFields: []
    }),

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            /* Get CSRF Token */
            if (this.submitOnSuccess) {
                let token = document.head.querySelector('meta[name="csrf-token"]');

                if (token) {
                    this.token = token.content;
                } else {
                    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
                }
            }
        },

        /* Execute when vue-dialog is confirmed */
        onDialogSuccess(event, dialog) {
            this.submit(event, dialog);
        },

        /* Execute ajax request */
        submit(event, dialog = null) {
            if (this.loading) {
                return;
            }
            if (!this.submitUrl) {
                if (dialog) {
                    dialog.close();
                }
                return;
            }

            return new Promise((resolve, reject) => {
                this.load(true);

                let params = this.params;
                let url = this.submitUrl;

                /* Get FormData if no parameter is provided in the props */
                if (!this.params) {
                    let form = event.target;

                    if (!form) {
                        form = event;
                    }

                    params = new FormData(form);
                }

                axios.post(url, params)
                    .then(response => {
                        const data = response.data;
                        if (data) {
                            /* Show if has success message */
                            if (data.message && !this.disableSuccessResponse) {

                                // console.log(this.successOptions);
                                this.parseSuccess(data.message, data.title, this.options.success);
                            }
                        }

                        /* Additional execution when response is successful */
                        this.success(data, event, response);
                        this.$emit('success', response);

                        resolve(response);
                    }).catch(error => {

                        /* Display Error message */
                        this.parseError(error, null, this.options.error);

                        if (this.validate) {
                            this.setInvalidRequest();
                        }

                        /* Additional execution when response fails */
                        this.error(event, error);

                        /* Fire emitter on component */
                        this.$emit('error', error);

                        reject(error);
                    }).then(() => {
                        this.load(false);

                        /* Stop loading of vue-dialog */
                        if (dialog) {
                            dialog.loading(false);
                            dialog.close();
                        }
                    });
            });
        },

        /* eslint-disable-next-line no-unused-vars */
        success(data, event, response) {
            /* Non-ajax form submit */
            if (this.submitOnSuccess) {
                setTimeout(() => {
                    event.target.submit();
                }, 500);
                return;
            }

            if (data) {
                /* Redirect to url */
                if (data.redirect && !this.disableRedirect) {
                    window.location.href = data.redirect;
                    return;
                }
            }

            /* Reset form on success */
            if (this.resetOnSuccess) {
                event.target.reset();
            }

            /* Fire Emitter to Sync other data tables */
            if (this.syncOnSuccess) {
                bus.$emit('sync-tables');
            }

            if (this.validate) {
                this.clearInvalidInputs();
            }
        },

        error(event, error) {
            this.setErrors(error);
        },

        setInvalidRequest() {
            this.clearInvalidInputs();
            this.setInvalidInputs();
        },

        clearInvalidInputs() {
            for (let i = this.inputFields.length - 1; i >= 0; i--) {
                this.inputFields[i].$data.invalid = false;
            }
        },

        setInvalidInputs() {
            this.inputFields = [];
            this.getFormInputs(this.$children);
            let forms = this.inputFields;
            let error;

            for (let i = forms.length - 1; i >= 0; i--) {
                error = this.getError(forms[i].name);
                if (error) {
                    forms[i].$data.invalid = true;
                    forms[i].$data.invalidText = error;
                }
            }
        },

        getFormInputs(elements) {
            elements.forEach(element => {
                console.log(element.validatable);
                if (element.validatable) {
                    this.inputFields.push(element);
                }

                if (this.array_count(element.$children) > 0) {
                    this.getFormInputs(element.$children);
                }
            });
        }
    }
};
</script>