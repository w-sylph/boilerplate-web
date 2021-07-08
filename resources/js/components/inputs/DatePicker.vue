<template>
    <div
        class="form-group"
        :class="{ 'is-invalid': isInvalid }"
    >
        <label v-if="label">
            {{ label }}
            <small
                v-if="labelNote"
                :class="labelNoteClass"
            >
                {{ labelNote }}
            </small>
        </label>

        <template v-if="mode == 'single'">
            <input
                ref="elem"
                :name="name"
                :placeholder="placeholder"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': isInvalid }"
                readonly
            >
        </template>

        <template v-if="mode == 'multiple'">
            <input
                ref="elem"
                :placeholder="placeholder"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': isInvalid }"
                readonly
            >

            <template v-if="array_count(inputValue) > 0">
                <input
                    v-for="date in inputValue"
                    :key="date"
                    :name="name"
                    :value="date"
                    type="hidden"
                >
            </template>

            <input
                v-if="array_count(inputValue) < 1"
                :name="name"
                class="form-control"
                type="hidden"
            >
        </template>

        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >
            {{ invalidMessage }}
        </span>
    </div>
</template>

<script>
/* Flatpickr Documentation: https://flatpickr.js.org/options/ */
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

import InputMixin from '../inputs/mixin.js';
import ArrayMixin from 'Mixins/array.js';

export default {
    name: 'date-picker',

    mixins: [ InputMixin, ArrayMixin ],

    props: {
        modelValue: {
            default: null,
            type: [ String, Array ]
        },

        /* 'single', 'multiple,', 'range' */
        mode: {
            default: 'single',
            type: String
        },

        enableTime: {
            default: true,
            type: Boolean
        },

        noCalendar: {
            default: false,
            type: Boolean
        },

        dateFormat: {
            default: 'Y-m-d H:i:S',
            type: String
        },

        defaultDate: {
            default: null,
            type: [ String, Date ]
        },

        minDate: {
            default: null,
            type: [ String, Date ]
        },

        disabledDays: {
            default: null,
            type: Array
        },

        disabledDates: {
            default: null,
            type: Array
        },

        hourIncrement: {
            default: 1,
            type: [ String, Number ]
        },

        minuteIncrement: {
            default: 5,
            type: [ String, Number ]
        },

        disableInput: {
            default: false,
            type: Boolean
        }
    },

    watch: {
        modelValue(value) {
            this.inputValue = value;
            this.setValue(value);
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            let options = this.getOptions();

            if (this.defaultDate) {
                options.defaultDate = this.defaultDate;
            }

            this.elem = flatpickr(this.$refs.elem, options);

            this.setValue(this.modelValue);
        },

        setValue(value) {
            switch (this.mode) {
                case 'single':
                    if (this.elem && moment(value).isValid()) {
                        this.elem.setDate(value);
                    } else {
                        this.elem.setDate(null);
                    }
                    break;
                case 'multiple':
                    if (this.array_count(value) > 0) {
                        this.elem.setDate(value.join(', '));
                    }
                    break;
            }
        },

        getOptions() {
            let $this = this;

            let options = {
                enableTime: this.enableTime,
                dateFormat: this.dateFormat,
                noCalendar: this.noCalendar,
                mode: this.mode,
                hourIncrement: this.hourIncrement,
                minuteIncrement: this.minuteIncrement,
                allowInput: this.disableInput,
                onChange: (selectedDates, dateStr) => {
                    let date = dateStr;

                    switch ($this.mode) {
                        case 'multiple':
                            console.log(date);

                            date = date.split(', ');
                            break;
                    }

                    if (this.invalid) {
                        this.invalid = false;
                    }

                    this.$emit('input', date);
                },
                "disable": [
                    (date) => {
                        if (this.inArray(date, this.disabledDates)) {
                            return true;
                        }

                        if (this.inArray(date.getDay(), this.disabledDays)) {
                            return true;
                        }

                        return false;
                    }
                ]
            };

            if (this.minDate) {
                options.minDate = this.minDate;
            }

            return options;
        }
    }
};
</script>

<style scoped>
.form-control[readonly] {
	background-color: #fff;
}
</style>