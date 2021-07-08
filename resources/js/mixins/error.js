/* eslint-disable vue/require-name-property */
// @vue/component
export default {

    props: {
        errorList: {
            default: () => [],
            type: Array
        }
    },

    data: () => ({
        errors: []
    }),

    watch: {
        errorList(value) {
            this.errors = value;
        }
    },

    methods: {
        /**
         * Get field error
         *
         * @return string
         **/
        getError(value) {
            let error = '';
            let errors = this.errors;

            if (errors) {
                if (errors[value]) {

                    /* Check if field exists */
                    error = errors[value];

                    if (error) {
                        if (typeof error === 'string') {
                            return error;
                        }

                        if (Array.isArray(error) && error.length > 0) {
                            return error[0];
                        }
                    }
                }
            }

            return error;
        },

        setErrors(error) {
            let errors = error.response ? error.response.data.errors : null;
            this.errors = [];

            this.$nextTick(() => {
                this.errors = errors ? errors : [];
            });
        },

        /**
         * Reset error variable
         *
         * IMPORTANT: Weird instance where the error var won't get updated immediately
         * causing the hasError(<field-name>) method to not show the error
         */
        resetErrors() {
            this.errors = {};
        },

        /**
         * Remove specified error
         *
         * @param string field
         */
        removeError(field) {
            delete this.errors[field];
        }
    }
};