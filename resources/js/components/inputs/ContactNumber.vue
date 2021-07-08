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
        <div class="input-group">
            <div
                v-if="isMobile"
                class="input-group-prepend"
            >
                <select
                    v-model="selected_code"
                    :name="name + '_country_code'"
                    class="custom-select rounded-left"
                >
                    <option
                        v-for="(code, index) in codes"
                        :key="'area-code-' + index"
                        :value="code.value"
                    >
                        {{ code.label }}
                    </option>
                </select>
            </div>

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

const TYPE_MOBILE = 'mobile';
const TYPE_TELEPHONE = 'telephone';

export default {
    name: 'contact-number',

    mixins: [ InputMixin ],

    props: {
        type: {
            default: 'telephone',
            type: String
        },

        areaCode: {
            default: null,
            type: [ String, Number ]
        }
    },

    data: () => ({
        pattern: '###########',
        selected_code: '63',
        codes: [
            {
                value: '63',
                label: '+63'
            }
        ]
    }),

    computed: {
        isMobile() {
            return this.type == TYPE_MOBILE;
        }
    },

    watch: {
        type(value) {
            this.setup(value);
        },

        areaCode(value) {
            this.selected_code = value ? value : this.selected_code;
        }
    },

    mounted() {
        this.setup(this.type);
    },

    methods: {
        setup(value) {
            switch (value) {
                case TYPE_MOBILE:
                    this.pattern = '##########';
                    break;
                case TYPE_TELEPHONE:
                    this.pattern = '########';
                    break;
            }
        }
    }
};
</script>

<style scoped>
.custom-select.rounded-left {
    -webkit-border-top-right-radius: 0 !important;
            border-top-right-radius: 0 !important;
    -webkit-border-bottom-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
}
</style>