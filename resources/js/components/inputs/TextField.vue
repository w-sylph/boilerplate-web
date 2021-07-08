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
            <small
                v-if="hint"
                ref="hint"
                class="fas fa-question-circle ml-1"
            />
        </label>

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
                :type="type"
                :name="name"
                :placeholder="placeholder"
                :maxlength="maxCounter"
                class="form-control"
                :class="[ invalidClass ]"
                :disabled="disabled"
                v-on="inputListeners"
            >

            <div
                v-if="$slots['append']"
                class="input-group-append"
            >
                <slot name="append" />
            </div>
        </div>

        <small
            v-if="!isInvalid && maxCounter"
            class="text-counter text-muted"
        >{{ textLength }} of {{ maxCounter }} characters used</small>

        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

export default {
    name: 'text-field',

    mixins: [ InputMixin ],

    props: {
        type: {
            default: 'text',
            type: String
        },

        maxCounter: {
            default: null,
            type: [ String, Number ]
        }
    },

    computed: {
        textLength() {
            return this.inputValue ? this.inputValue.length : 0;
        },

        inputCustomListeners() {
            return {
                input: () => {
                    this.inputChange();
                }
            };
        }
    }

};
</script>

<style scoped>
.is-invalid .text-counter {
    display: none;
}
</style>