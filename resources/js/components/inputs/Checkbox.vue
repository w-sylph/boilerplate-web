<template>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input
                :id="id"
                v-model="inputValue"
                :value="value"
                type="checkbox"
                :name="name"
                :placeholder="placeholder"
                class="custom-control-input"
                :class="[ invalidClass ]"
                :disabled="disabled"
                @change="inputChange"
            >
            <label
                :for="id"
                class="custom-control-label"
            >{{ label }} <small
                v-if="labelNote"
                :class="labelNoteClass"
            >{{ labelNote }}</small></label>
            <span
                v-if="isInvalid"
                class="invalid-feedback d-block"
            >{{ invalidMessage }}</span>
        </div>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

export default {
    name: 'checkbox',

    mixins: [ InputMixin ],

    props: {
        modelValue: {
            default: true,
            type: [ String, Number, Boolean, Array ]
        },

        value: {
            default: true,
            type: [ String, Number, Boolean, Array ]
        }
    },

    computed: {
        id() {
            return 'checkbox-' + this._uid;
        }
    },

    watch: {
        modelValue(value) {
            this.setInput(value);
        }
    },

    mounted() {
        this.setInput(this.modelValue);
    },

    methods: {
        setInput(value) {
            if (this.value === true) {
                this.inputValue = value ? true : false;
                this.inputChange();
            } else {
                this.inputValue = value;
            }
        }
    }
};
</script>

<style scoped>
.is-invalid .text-counter {
    display: none;
}
</style>