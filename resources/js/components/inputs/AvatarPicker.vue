<template>
    <div class="d-inline-block">
        <div class="d-inline-block rounded border p-1">
            <label
                :for="id"
                class="position-relative overlay-hoverable mb-0 pb-0"
            >
                <div
                    v-if="! value"
                    class="card d-flex justify-content-center align-items-center bg-light"
                    style="width: 100px; height: 100px;"
                >
                    <h1 class="font-weight-bold m-0">+</h1>
                </div>
                <img
                    v-if="thumbnail"
                    :src="thumbnail"
                    class="img-fluid rounded"
                    width="120px"
                    height="auto"
                >
                <input
                    :id="id"
                    type="file"
                    :name="name"
                    style="display: none;"
                    @change="inputChange"
                >
                <div class="overlay-hidden bg-overlay d-flex justify-content-center align-items-center text-center rounded">
                    <span class="text-white w-100">
                        {{ placeholder }}
                    </span>
                </div>
            </label>
        </div>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

export default {
    name: 'avatar-picker',

    mixins: [ InputMixin ],

    props: {
        value: {
            default: null,
            type: String
        },

        name: {
            default: null,
            type: String
        },

        placeholder: {
            default: 'Change Image',
            type: String
        }
    },

    data: () => ({
        image: null
    }),

    computed: {
        id() {
            return `${this.name}-${this._uid}`;
        },

        thumbnail() {
            return this.image || this.value;
        }
    },

    watch: {
        value(value) {
            if (value != this.image) {
                this.image = null;
            }
        }
    },

    methods: {
        inputChange(event) {
            let files = event.target.files;
            this.image = URL.createObjectURL(files[0]);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '../../../sass/components/_overlay.scss';
</style>