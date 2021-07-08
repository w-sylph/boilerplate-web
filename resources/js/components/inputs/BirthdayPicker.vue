<template>
    <div class="form-group">
        <label
            v-if="label"
            class="theme--text"
        >
            {{ label }}
            <small
                v-if="labelNote"
                class="theme--text"
                :class="labelNoteClass"
            >{{ labelNote }}</small>
        </label>
        <div class="row no-gutters">
            <div class="col-5 pr-2">
                <select
                    v-model="month"
                    class="custom-select"
                    :disabled="disabled"
                    :class="[ invalidClass ]"
                    @change="inputChange"
                >
                    <option
                        :value="null"
                        hidden
                    >
                        Month
                    </option>
                    <option
                        v-for="(monthItem, index) in months"
                        :key="`month-${index}`"
                        :value="monthItem.value"
                    >
                        {{ monthItem.text }}
                    </option>
                </select>
            </div>
            <div class="col-3">
                <input
                    v-model="day"
                    v-mask="'##'"
                    type="text"
                    class="form-control"
                    placeholder="Day"
                    :disabled="disabled"
                    :class="[ invalidClass ]"
                    @change="inputChange"
                >
            </div>
            <div class="col-4 pl-2">
                <div class="input-group">
                    <input
                        v-model="year"
                        v-mask="'####'"
                        type="text"
                        class="form-control"
                        placeholder="Year"
                        :disabled="disabled"
                        :class="[ invalidClass ]"
                        @change="inputChange"
                    >
                    <div class="input-group-append">
                        <div
                            ref="datepicker"
                            class="btn btn-light border"
                        >
                            <i class="fas fa-calendar-alt" />
                        </div>
                    </div>
                </div>
            </div>

            <input
                v-model="inputValue"
                type="hidden"
                class="form-control"
                readonly
                :name="name"
            >
            <span
                v-if="isInvalid"
                class="invalid-feedback d-block"
            >{{ invalidMessage }}</span>
        </div>
    </div>
</template>

<script>
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

import InputMixin from './mixin.js';
import DateMixin from '../../mixins/date.js';

export default {
    name: 'birthday-picker',

    mixins: [ DateMixin, InputMixin ],

    data: () => ({
        month: null,
        day: null,
        year: null,

        months: [
            { value: '01', text: 'January' },
            { value: '02', text: 'February' },
            { value: '03', text: 'March' },
            { value: '04', text: 'April' },
            { value: '05', text: 'May' },
            { value: '06', text: 'June' },
            { value: '07', text: 'July' },
            { value: '08', text: 'August' },
            { value: '09', text: 'September' },
            { value: '10', text: 'October' },
            { value: '11', text: 'November' },
            { value: '12', text: 'December' }
        ]
    }),

    computed: {
        /* Disable automatic changes due to conflict with other components using 3rd party plugin such as ckeditor */
        disableChangeEvent() {
            return false;
        }
    },

    watch: {
        modelValue(value) {
            this.inputValue = this.toDate(value, 'YYYY-MM-DD');
            this.year = this.toDate(value, 'YYYY');
            this.month = this.toDate(value, 'MM');
            this.day = this.toDate(value, 'DD');

            if (value && this.datepicker) {
                this.datepicker.setDate(value);
            }
        }
    },

    mounted() {
        this.setupDatepicker();
    },

    methods: {
        setupDatepicker() {
            let options = this.getOptions();

            this.datepicker = flatpickr(this.$refs.datepicker, options);
            if (this.value) {
                this.datepicker.setDate(this.value);
            }
        },

        getOptions() {
            let options = {
                enableTime: false,
                dateFormat: 'Y-m-d',
                onChange: (selectedDates, dateStr) => {
                    this.year = this.toDate(dateStr, 'YYYY');
                    this.month = this.toDate(dateStr, 'MM');
                    this.day = this.toDate(dateStr, 'DD');

                    if (this.invalid) {
                        this.invalid = false;
                    }

                    this.$emit('input', dateStr);
                }
            };

            return options;
        },

        inputChange() {
            let result = '';
            let valid = 0;

            if (this.year) {
                result += this.year;
                valid++;
            }

            if (this.month) {
                result += '-' + this.month;
                valid++;
            }

            if (this.day) {
                result += '-' + this.day;
                valid++;
            }

            if (moment(result).isValid() && valid > 2) {
                this.inputValue = this.toDate(result, 'YYYY-MM-DD');
                this.datepicker.setDate(result);
                this.$emit('input', this.toDate(result, 'YYYY-MM-DD'));
            } else {
                this.inputValue = '';
            }
        }
    }
};
</script>