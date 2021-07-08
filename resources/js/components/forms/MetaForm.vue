<template>
    <div class="card border-0">
        <div class="card-header">
            <h5 class="font-weight-bold m-0 d-inline-block">
                Search engine listing preview
            </h5>
            <span class="float-right">
                <button
                    v-if="!visible"
                    class="btn btn-link"
                    type="button"
                    @click="show"
                >Edit website SEO</button>
            </span>
            <p class="mt-3 mb-0">
                Add a description to see how this product might appear in a search engine listing
            </p>
        </div>
        <transition name="fade">
            <div
                v-show="visible"
                class="card-body"
            >
                <div class="row">
                    <text-field
                        v-model="meta.title"
                        class="col-12 col-md-6"
                        label="Title"
                        name="meta_title"
                        max-counter="70"
                    />

                    <text-field
                        v-model="meta.keywords"
                        class="col-12 col-md-6"
                        label="Keywords"
                        name="meta_keywords"
                    />

                    <text-field
                        v-model="meta.description"
                        class="col-12"
                        label="Description"
                        name="meta_description"
                        max-counter="320"
                    />


                    <text-field
                        v-if="hasSlug"
                        v-model="meta.slug"
                        class="col-12"
                        label="URL and handle"
                        label-note="(changes may cause some broken link)"
                        label-note-class="text-danger"
                        name="slug"
                    >
                        <template #prepend>
                            <span class="input-group-text bg-white"><small class="text-muted">{{ handle }}</small></span>
                        </template>
                    </text-field>
                </div>

                <div class="row">
                    <div class="border-top w-100 mb-3" />

                    <div class="col-12">
                        <h5 class="font-weight-bold">
                            Open Graph (OG)
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <text-field
                        v-model="meta.og_title"
                        class="col-12"
                        label="Title"
                        name="meta_og_title"
                    />

                    <text-field
                        v-model="meta.og_description"
                        class="col-12"
                        label="Description"
                        name="meta_og_description"
                    />

                    <file-picker
                        :value="meta.path"
                        class="form-group col-sm-12 col-md-12 mt-2"
                        label="Image"
                        name="meta_og_image"
                        note="Recommended Size: 1200 x 630"
                        placeholder="Choose a File"
                    />
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import FilePicker from '../inputs/FilePicker.vue';
import TextField from '../inputs/TextField.vue';

export default {
    name: 'meta-form',

    components: {
        TextField,
        FilePicker
    },

    props: {
        item: {
            default: null,
            type: Object
        },

        slug: {
            default: null,
            type: String
        },

        handle: {
            default: null,
            type: String
        },

        hasSlug: {
            default: false,
            type: Boolean
        }
    },

    data() {
        return {
            visible: false,
            meta: {}
        };
    },

    watch: {
        item(value) {
            this.meta = value;
        },

        slug(value) {
            this.meta.slug = value;
        }
    },

    mounted() {
        if (!this.meta.id) {
            this.meta = this.item;
        }
    },

    methods: {
        show() {
            this.visible = true;
        },

        toggle() {
            this.visible = !this.visible;
        }
    }
};
</script>