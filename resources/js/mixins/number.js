/* eslint-disable vue/require-name-property */
// @vue/component
export default {
    methods: {
        round(value) {
            return Math.round(value);
        },

        toInt(value) {
            value = parseInt(value);
            return isNaN(value) ? 0 : value;
        },

        toFloat(value) {
            return parseFloat(value);
        },

        toMoney(value, prefix = '₱') {
            if (!value) {
                return;
            }

            return prefix + ' ' + (parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')).toString();
        },

        percentDifference(currentPrice, originalPrice) {
            let result = 0;
            let computation = 0;
            originalPrice = parseFloat(originalPrice);
            currentPrice = parseFloat(currentPrice);

            computation = (currentPrice / originalPrice) * 100;
            computation = 100 - computation;
            computation = Math.abs(computation);
            computation = Math.floor(computation);
            result = computation;

            return result;
        },

        formatTelephoneNumber(telephoneNumber) {
            if (telephoneNumber) {
                //normalize string and remove all unnecessary characters
                telephoneNumber = telephoneNumber.replace(/[^\d]/g, "");

                if (telephoneNumber.length == 8) {
                    //reformat and return phone number
                    return telephoneNumber.replace(/(\d{4})(\d{4})/, " $1-$2");
                }
            }

            return null;
        },

        /* currently parsing PH mobile number only */
        formatMobileNumber(mobileNumber, countryCode) {
            let result = null;

            if (mobileNumber) {
                //normalize string and remove all unnecessary characters
                mobileNumber = mobileNumber.replace(/[^\d]/g, "");

                if (mobileNumber.length == 10) {
                    //reformat and return phone number
                    result = mobileNumber.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
                }
            }

            if (countryCode) {
                result = `(+${countryCode}) ${result}`;
            }

            return result;
        }
    }
};