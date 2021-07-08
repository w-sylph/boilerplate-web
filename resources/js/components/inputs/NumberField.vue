<template>
    <div class="form-group">
        <label v-if="label || labelNote">{{ label }} <small
            v-if="labelNote"
            :class="labelNoteClass"
        >{{ labelNote }}</small></label>

        <div :class="{ 'input-group': $slots['append'] || $slots['prepend'] }">
            <div
                v-if="$slots['prepend']"
                class="input-group-prepend"
            >
                <slot name="prepend" />
            </div>

            <input
                v-model="inputValue"
                v-bind="$attrs"
                type="text"
                :name="name"
                class="form-control"
                :class="[ invalidClass ]"
                :placeholder="placeholder"
                :disabled="disabled"
                :max="max"
                v-on="inputListeners"
            >

            <div
                v-if="$slots['append']"
                class="input-group-append"
            >
                <slot name="append" />
            </div>
        </div>

        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

export default {
    name: 'number-field',

    mixins: [ InputMixin ],

    props: {
        modelValue: {
            default: null,
            type: [ Number, String ]
        },

        type: {
            default: 'number',
            type: String
        },

        max: {
            default: null,
            type: [ Number, String, Boolean ]
        }
    },

    data: () => ({
        pattern: /^[0-9]*\.?[0-9]*$/,
        format: /^\d*\.?\d*$/
    }),

    computed: {
        inputCustomListeners() {
            return {
                keypress: (event) => {
                    this.validate(event);
                },

                paste: (event) => {
                    this.validate(event);
                },

                input: (event) => {
                    this.validate(event);
                },

                blur: () => {
                    // this.validateFormat();
                }
            };
        }
    },

    watch: {
        modelValue(value) {
            if (this.belowMaxValue(value)) {
                this.inputValue = value;
            }
        }
    },

    mounted() {
        this.setup(this.type);
    },

    methods: {
        setup(type) {
            switch (type) {
                case 'number':
                    this.pattern = /^[0-9]*\.?[0-9]*$/;
                    this.format = /^\d*\.?\d*$/;
                    break;
                case 'integer':
                    this.pattern = /^[0-9]+$/;
                    this.format = /^[0-9]+$/;
                    break;
                case 'real-integer':
                    this.pattern = /^[0-9-]*$/;
                    this.format = /^[-]?[0-9]+$/;
                    break;
            }
        },

        /* validate format on blur */
        validate(event) {
            let key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

            console.log(! this.belowMaxValue(event.target.value));
            if (! this.belowMaxValue(event.target.value)) {
                this.inputValue = this.max;
                event.preventDefault();
            }

            if (event.which != 13 || event.keyCode != 13) {
                if (! this.pattern.test(key)) {
                    event.preventDefault();
                }
            }

        },

        /* validate format on blur */
        validateFormat() {
            if (this.inputValue) {
                if (this.belowMaxValue(this.inputValue)) {
                    this.inputValue = this.max;
                    return;
                }

                if (! this.format.test(this.inputValue)) {
                    switch (this.type) {
                        case 'number':
                            this.inputValue = parseFloat(this.inputValue);
                            this.inputChange();
                            break;
                        case 'real-integer':
                            this.inputValue = parseInt(this.inputValue);
                            this.inputChange();
                            break;
                    }
                }
            }
        },

        belowMaxValue(value) {
            if (! value) {
                return true;
            }

            if (this.max === false || this.max === null) {
                return true;
            }

            let result = false;

            if (this.max) {
                if (parseInt(value) < parseInt(this.max)) {
                    result = true;
                }
            }

            return result;
        }
    }
};
</script>