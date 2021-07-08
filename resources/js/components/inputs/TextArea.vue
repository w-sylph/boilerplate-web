<template>
    <div class="form-group">
        <label
            v-if="label"
            class="theme--text"
        >
            {{ label }}
            <small
                v-if="labelNote"
                :class="labelNoteClass"
            >
                {{ labelNote }}
            </small>
        </label>
        <textarea
            v-model="inputValue"
            :name="name"
            :placeholder="placeholder"
            class="form-control input-sm"
            :class="[ invalidClass ]"
            :disabled="disabled"
            @input="inputChange"
        />
        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

export default {
    name: 'text-area',

    mixins: [ InputMixin ]
};
</script>