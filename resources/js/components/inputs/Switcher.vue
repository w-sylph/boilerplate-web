<template>
    <div class="custom-control custom-switch mr-2">
        <input
            :id="'re-order-btn-' + _uid"
            v-model="inputValue"
            type="checkbox"
            class="custom-control-input"
            @change="inputChange"
        >
        <label
            class="custom-control-label"
            :for="'re-order-btn-' + _uid"
        >{{ label }}</label>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

export default {
    name: 'switcher',

    mixins: [ InputMixin ],

    props: {
        modelValue: {
            default: false,
            type: Boolean,
            required: true
        }
    }
};
</script>