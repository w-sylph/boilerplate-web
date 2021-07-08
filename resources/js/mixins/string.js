/* eslint-disable vue/require-name-property */
// @vue/component
export default {
    methods: {
        humanize(string) {
            let frags = string.split('_');

            for (let i = 0; i < frags.length; i++) {
                frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
            }

            return frags.join(' ');
        },

        truncate(string, length = 100, ellipsis = '...') {
            if (string.length <= length) {
                return string;
            }

            let subString = string.substr(0, length-1);
            return subString.substr(0, subString.lastIndexOf(' ')) + ellipsis;
        },

        titleCase(string) {
            let sentence = string.toLowerCase().split(" ");

            for (var i = 0; i < sentence.length; i++){
                sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
            }

            return sentence.join(" ");
        },

        lowerCase(string) {
            if (string) {
                string = string.toLowerCase();
            }

            return string;
        },

        randomString(length) {
           let result           = '';
           let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
           let charactersLength = characters.length;

           for (let i = 0; i < length; i++) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
           }
           return result;
        },

        uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                let r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        },

        /* Get formatted address for displaying purposes */
        getAddress(address) {
            let result = [];
            let value = null;

            value = address.unit_number;
            if (value) {
                result.push(value);
            }

            value = address.primary_address;
            if (value) {
                result.push(value);
            }

            value = address.barangay;
            if (value) {
                result.push(value);
            }

            if (address.city || address.zip_code) {
                let string = '';

                if (address.city) {
                    string += address.city + ' ';
                }

                if (address.zip_code) {
                    string += address.zip_code;
                }

                result.push(string);
            }

            return result.join(', ');
        }
    }
};