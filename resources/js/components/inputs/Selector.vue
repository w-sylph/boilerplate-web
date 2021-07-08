<template>
    <div
        class="form-group"
        :class="[ invalidClass ]"
    >
        <label
            v-if="label"
            class="theme--text"
        >{{ label }} <small
            v-if="labelNote"
            class="theme--text"
            :class="labelNoteClass"
        >{{ labelNote }}</small></label>
        <select
            ref="elem"
            v-model="inputValue"
            :name="name"
        >
            <option
                v-for="(item, index) in items"
                :key="`item-${index}`"
                :value="item[itemValue] ? item[itemValue] : item"
            >
                {{ item[itemText] ? item[itemText] : item }}
            </option>
        </select>

        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
import 'select2/dist/css/select2.css';
import '@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css';
import 'select2';

import InputMixin from './mixin.js';
import ArrayMixin from '../../mixins/array.js';

export default {
    name: 'selector',

    mixins: [ InputMixin, ArrayMixin ],

    props: {
        modelValue: {
            default: null,
            type: [ String, Number, Array, Boolean ]
        },

        items: {
            default: null,
            type: Array
        },

        multiple: {
            default: false,
            type: Boolean
        },

        itemText: {
            default: 'label',
            type: String
        },

        itemValue: {
            default: 'value',
            type: String
        },

        taggable: {
            type: Boolean,
            default: false
        }
    },

    data: () => ({
        elem: null
    }),

    computed: {
        options() {
            let options = {
                theme: 'bootstrap4',
                allowClear: true,
                multiple: this.multiple,
                closeOnSelect: ! this.multiple,
                placeholder: this.placeholder || ' ',
                disabled: this.disabled,
                tags: this.taggable,
                width: '100%'
            };

            if (this.taggable) {
                options.createTag = function(params) {
                    // Don't offset to create a tag if there is no @ symbol
                    return {
                        id: params.term,
                        text: params.term
                    };
                };
            }

            return options;
        },

        /* Disable automatic changes due to conflict with other components using 3rd party plugin such as ckeditor */
        disableChangeEvent() {
            return true;
        }
    },

    watch: {
        modelValue(value) {
            if (this.elem) {
                this.inputChange(value);
            }
        },

        disabled(value) {
            if (this.elem) {
                this.elem.select2(Object.assign(this.options, { disabled: value }));
            }
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            this.elem = $(this.$refs.elem);

            this.elem.select2(this.options);

            /* Add event listeners to select2 */
            this.elem.on('select2:select', this.select);
            this.elem.on('select2:unselect', this.unselect);
            this.elem.on('select2:clearing', this.clear);


            /* Set initial value */
            let value = this.modelValue;

            if (! value || this.array_count(value) < 1) {
                value = this.multiple ? [] : null;
            }

            this.inputChange(value);
        },

        select(event) {
            let value = event.params.data.id;

            if (this.multiple) {
                if (! this.inArray(value, this.inputValue)) {
                    this.inputValue.push(value);
                }
            } else {
                this.inputValue = value;
            }

            this.inputChange(value);
        },

        unselect(event) {
            let value = event.params.data.id;

            if (this.multiple) {
                if (this.array_count(this.inputValue) > 0) {
                    value = this.inputValue.filter((obj) => {
                        obj = obj[this.itemValue] || obj;
                        return obj != value;
                    });
                } else {
                    value = [];
                }
            } else {
                this.inputValue = value;
            }

            this.inputChange(value);
        },

        clear(event) {
            event.preventDefault();
            let value = this.multiple ? [] : null;
            this.elem.val({}).trigger('change');
            this.$emit('input', value);
        },

        inputChange(value) {
            this.$nextTick(() => {
                this.inputValue = value;

                if (this.elem) {
                    this.elem.val(value).trigger('change');
                }

                this.$emit('input', value);
            });
        }
    }
};
</script>

<style>
.form-group.is-invalid .select2-container--bootstrap4 .select2-selection {
    border-color: #dc3545;
}
</style>