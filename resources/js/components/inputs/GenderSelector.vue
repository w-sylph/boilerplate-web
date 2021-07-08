<template>
    <div class="form-group">
        <label v-if="label">
            {{ label }}
            <small
                v-if="labelNote"
                :class="labelNoteClass"
            >{{ labelNote }}</small>
            <small
                v-if="hint"
                ref="hint"
                class="fas fa-question-circle ml-1"
            />
        </label>

        <select
            v-model="inputValue"
            name="gender"
            class="form-control"
            :class="[ invalidClass ]"
        >
            <option value="Male">
                Male
            </option>
            <option value="Female">
                Female
            </option>
        </select>

        <span
            v-if="isInvalid"
            class="invalid-feedback d-block"
        >{{ invalidMessage }}</span>
    </div>
</template>

<script>
import InputMixin from 'Components/inputs/mixin.js';

export default {
    name: 'gender-selector',

    mixins: [ InputMixin ]
};
</script>