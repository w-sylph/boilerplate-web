/* eslint-disable vue/require-name-property */
// @vue/component
export default {
    methods: {
        inArray(value, array, column = null) {
            if (array) {
                for (var i = array.length - 1; i >= 0; i--) {
                    if (column) {
                        if (value == array[i][column]) {
                            return true;
                        }
                    }

                    if (value == array[i]) {
                        return true;
                    }
                }
            }

            return false;
        },

        isArray(value) {
            return Array.isArray(value);
        },

        array_count(value) {
            if (!value) {
                return 0;
            }

            if (Array.isArray(value)) {
                return value.length;
            }

            return 0;
        },

        array_unique(array) {
            return array.filter((x, i, a) => a.indexOf(x) == i);
        },

        array_pluck(array, key) {
            let plucked = [];
            for (let i = 0; i < array.length; i++){
                plucked[i] = array[i][key];
            }

            return plucked;
        },

        arraySearch(array, value, column = null) {
            for (let i = 0; i < array.length; i++) {
                if (array[i][column] == value) {
                    return i;
                }
            }

            return false;
        },

        empty(value) {
            let type = typeof value;

            if (type === 'undefined') {
                return true;
            }
            if (type === 'boolean') {
                return !value;
            }
            if (value === null) {
                return true;
            }
            if (value === undefined) {
                return true;
            }
            if (value instanceof Array) {
                if (value.length < 1) {
                    return true;
                }
            } else if (type === 'string') {
                if (value.length < 1) {
                    return true;
                }

                if (value === '0') {
                    return true;
                }

                if (/^\s*$/.test(value)) {
                    return true;
                }
            } else if (type === 'object') {
                if (Object.keys(value).length < 1) {
                    return true;
                }

                if (Object.values(value).every(el => el === undefined)) {
                    return true;
                }

                if (value === 0) {
                    return true;
                }
            }

            return false;
        }
    }
};