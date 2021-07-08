<template>
    <div class="row">
        <div
            v-if="isText"
            class="form-group col-sm-12 col-md-12"
        >
            <label v-if="label">{{ label }} <small v-if="note">({{ note }})</small></label>
            <input
                :value="content"
                :name="name"
                type="text"
                class="form-control input-sm"
            >
        </div>

        <file-picker
            v-if="isImage"
            :value="content"
            class="form-group col-sm-12 col-md-12 mt-2"
            :label="label"
            :name="name"
            :note="note"
            placeholder="Choose an Image"
        />

        <text-editor
            v-if="isHtml"
            v-model="content"
            class="col-sm-12"
            :label="label"
            :name="name"
            :note="note"
            row="3"
        />
    </div>
</template>

<script>
import FilePicker from '../../../components/inputs/FilePicker.vue';
import TextEditor from '../../../components/inputs/TextEditor.vue';
import StringHelper from '../../../mixins/string.js';

export default {
    name: 'page-item-content',

    components: {
        FilePicker,
        TextEditor
    },

    mixins: [ StringHelper ],

    model: {
        prop: 'value',
        event: 'change'
    },

    props: {
        type: {
            default: null,
            type: String
        },

        name: {
            default: null,
            type: String
        },

        value: {
            default: null,
            type: String
        },

        label: {
            default: null,
            type: String
        },

        note: {
            default: null,
            type: String
        }
    },

    data() {
        return {
            content: null
        };
    },

    computed: {
        isText() {
            return this.type === 'TEXT';
        },

        isHtml() {
            return this.type === 'HTML';
        },

        isImage() {
            return this.type === 'IMAGE';
        }
    },

    watch: {
        content(value) {
            this.$emit('change', value);
        },

        value(value) {
            this.content = value;
        }
    },
    mounted() {
        if (!this.content) {
            this.content = this.value;
        }
    }
};
</script>