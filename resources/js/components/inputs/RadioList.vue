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
            >{{ labelNote }}</small>
            <small
                v-if="hint"
                ref="hint"
                class="fas fa-question-circle ml-1"
            />
        </label>
        <div
            v-for="(item, index) in items"
            :key="`radio-list-${index}`"
            class="custom-control custom-radio"
            :class="[ listStyle ]"
        >
            <input
                :id="`${id}-${index}`"
                v-model="inputValue"
                :value="item[itemValue]"
                type="radio"
                :name="name"
                class="custom-control-input"
                @change="inputChange"
            >
            <label
                class="custom-control-label theme--text"
                :for="`${id}-${index}`"
            >
                <slot :item="item">{{ item[itemText] }}</slot>
            </label>
            <p
                v-if="itemDescription && item[itemDescription]"
                class="text-muted"
            >
                {{ item[itemDescription] }}
            </p>
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
    name: 'radio-list',

    mixins: [ InputMixin ],

    props: {
        modelValue: {
            default: null,
            type: [ String, Number ]
        },

        items: {
            default: null,
            type: Array
        },

        name: {
            default: null,
            type: String
        },

        itemText: {
            default: 'label',
            type: String
        },

        itemValue: {
            default: 'value',
            type: String
        },

        itemDescription: {
            default: null,
            type: String
        },

        inline: {
            default: false,
            type: Boolean
        }
    },

    computed: {
        id() {
            return 'radio-list-' + this._uid;
        },

        listStyle() {
            return this.inline ? 'custom-control-inline' : '';
        }
    }
};
</script>