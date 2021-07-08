<template>
    <div class="form-group">
        <label
            v-if="label"
            class="theme--text"
        >{{ label }} <small
            v-if="labelNote"
            :class="labelNoteClass"
            class="theme--text"
        >{{ labelNote }}</small></label>
        <input
            v-model="inputValue"
            v-mask="pattern"
            type="text"
            :name="name"
            class="form-control"
            :placeholder="placeholder"
            :class="[ invalidClass ]"
            @input="inputChange"
        >
        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

export default {
    name: 'zip-code',

    mixins: [ InputMixin ],

    props: {
        modelValue: {
            default: null,
            type: [ String, Number ]
        }
    },

    data: () => ({
        pattern: '####'
    })
};
</script>