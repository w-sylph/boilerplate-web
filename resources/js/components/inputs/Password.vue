<template>
    <div class="form-group">
        <label
            v-if="label"
            class="theme--text"
        >{{ label }} <small
            v-if="labelNote"
            :class="labelNoteClass"
        >{{ labelNote }}</small></label>
        <div class="input-group">
            <input
                ref="elem"
                :name="name"
                :type="hidden ? 'password' : 'text'"
                class="form-control border-right-0"
                :class="[ invalidClass ]"
                :placeholder="placeholder"
                @keypress="validate"
            >

            <div class="input-group-append">
                <button
                    type="button"
                    tabindex="-1"
                    class="btn btn-white text-muted border border-left-0"
                    @click="hidden = !hidden"
                >
                    <i
                        v-if="hidden"
                        class="fa fa-eye-slash"
                    />
                    <i
                        v-if="!hidden"
                        class="fa fa-eye"
                    />
                </button>
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

export default {
    name: 'password',

    mixins: [ InputMixin ],

    data: () => ({
        hidden: true,
        elem: null
    }),

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            this.elem = $(this.$refs.elem);
            this.elem.tooltip({
                placement: 'left',
                title: 'Capslock is ON',
                trigger: 'manual'
            });
        },

        validate(event) {
            let capsLock = false;
            const IS_MAC = /Mac/.test(navigator.platform);
            const charCode = event.charCode;
            const shiftKey = event.shiftKey;

            if (charCode >= 97 && charCode <= 122){
                capsLock = shiftKey;
            } else if (charCode >= 65 && charCode <= 90 && !(shiftKey && IS_MAC)){
                capsLock = !shiftKey;
            }

            if (capsLock) {
                this.elem.tooltip('show');
            } else {
                this.elem.tooltip('hide');
            }
        }
    }
};
</script>