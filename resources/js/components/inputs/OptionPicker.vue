<template>
    <div class="form-group">
        <label
            v-if="label"
            class="d-block"
        >
            {{ label }}
            <small
                v-if="labelNote"
                :class="labelNoteClass"
            >{{ labelNote }}</small>
        </label>

        <button
            v-if="showButton"
            type="button"
            class="btn btn-light border mr-2 mb-2"
            @click="open()"
        >
            {{ buttonLabel }}
        </button>

        <input
            v-if="showInput"
            v-model="tagvalue"
            :placeholder="placeholder"
            class="form-control mb-2"
            :class="[ invalidClass ]"
            :disabled="disabled"
            @change="storeTag"
            @keypress="keypress"
            @blur="storeTag"
            @keyup.enter="storeTag"
        >

        <div
            ref="sortable"
            class="d-inline-block"
        >
            <div
                v-for="option_item in optionItems"
                :key="'option-button-' + option_item.id"
                :data-id="option_item.id"
                class="d-inline-block"
            >
                <img
                    v-if="isImage"
                    :src="option_item.value"
                    class="rounded d-inline-block mb-2"
                    width="32"
                    height="32"
                >

                <div class="btn-group mr-2 mb-2">
                    <button
                        v-if="isColor"
                        type="button"
                        class="btn border"
                        :style="'width: 32px; background: ' + option_item.value + ';'"
                    >
                        &nbsp;
                    </button>
                    <button
                        type="button"
                        class="btn btn-light border"
                        @click="open(option_item.id)"
                    >
                        {{ option_item.name }}
                    </button>
                    <button
                        type="button"
                        class="btn btn-light border"
                        @click="remove(option_item.id)"
                    >
                        <i class="fas fa-times" />
                    </button>
                </div>

                <input
                    v-if="isTag"
                    type="hidden"
                    :name="name + '[' + option_item.id + ']'"
                    :value="option_item.name"
                >
            </div>
        </div>

        <small
            v-if="isSortable"
            class="text-muted d-block mb-2"
        >(Drag to sort)</small>

        <template v-if="isColor">
            <div
                v-for="option_item in optionItems"
                :key="'option-color-' + option_item.id"
            >
                <input
                    type="hidden"
                    :name="itemName + '[' + option_item.id + ']'"
                    :value="option_item.value"
                >
                <input
                    type="hidden"
                    :name="valueName + '[' + option_item.id + ']'"
                    :value="option_item.name"
                >
            </div>
        </template>


        <template v-if="isImage">
            <div
                v-for="option_item in optionItems"
                :key="'option-image-' + option_item.id"
            >
                <input
                    type="hidden"
                    :name="itemName + '[' + option_item.id + ']'"
                    :value="option_item.value"
                >
                <input
                    type="hidden"
                    :name="valueName + '[' + option_item.id + ']'"
                    :value="option_item.name"
                >
            </div>
        </template>

        <div
            v-for="option_item in optionItems"
            :key="`option-errors-${option_item.id}`"
        >
            <span class="d-block invalid-feedback">
                {{ getError(replace(name) + option_item.id) }}
            </span>
            <span class="d-block invalid-feedback">
                {{ getError(replace(itemName) + option_item.id) }}
            </span>
            <span class="d-block invalid-feedback">
                {{ getError(replace(valueName) + option_item.id) }}
            </span>
        </div>

        <modal
            ref="modal"
            backdrop="static"
            small
        >
            <template #title>
                {{ modalHeader }}
            </template>

            <template #body>
                <div class="row">
                    <div
                        v-if="isImage"
                        class="form-group col-12 text-center"
                    >
                        <img
                            v-if="selected_value"
                            :src="selected_value"
                            class="img-fluid mb-3"
                        >
                        <label
                            :for="'image-field-' + id"
                            class="image border w-100 text-center pt-2 border-dashed"
                            @click="selectImage"
                        >
                            <h1>
                                <i class="fas fa-image" />
                            </h1>
                            <p class="font-weight-bold">
                                Select an Image
                            </p>
                        </label>
                        <input
                            :id="'image-field-' + id"
                            type="file"
                            accept="image/x-png,image/gif,image/jpeg"
                            class="d-none"
                            @change="getImage"
                        >
                        <small
                            v-if="errorValue"
                            class="text-danger d-block"
                        >{{ errorValue }}</small>
                    </div>

                    <color-picker
                        v-if="isColor"
                        v-model="selected_value"
                        class="col-12"
                        type="hidden"
                        label="Color"
                        use-as-button
                        show-always
                        inline
                        :error="errorValue"
                    />

                    <text-field
                        v-model="selected_name"
                        class="col-12"
                        label="Name"
                        :error="errorName"
                        @keydown="keydown"
                    />
                </div>
            </template>

            <template #footer>
                <button
                    type="button"
                    class="btn btn-light border"
                    @click="close"
                >
                    Close
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="submit"
                >
                    Confirm
                </button>
            </template>
        </modal>
    </div>
</template>

<script>
import InputMixin from './mixin.js';

import ArrayMixin from 'Mixins/array.js';
import ErrorMixin from 'Mixins/error.js';
import StringMixin from 'Mixins/string.js';
import SortableMixin from 'Mixins/sortable.js';
import BinaryMixin from 'Mixins/binary.js';

import TextField from './TextField.vue';
import ColorPicker from './ColorPicker.vue';
import Modal from 'Components/dialogs/Modal.vue';

const MODE_CREATE = 'MODE_CREATE';
const MODE_UPDATE = 'MODE_UPDATE';

export default {
    name: 'option-picker',

    components: {
        TextField,
        ColorPicker,
        Modal
    },

    mixins: [ InputMixin, ArrayMixin, StringMixin, ErrorMixin, SortableMixin, BinaryMixin ],

    props: {
        type: {
            default: 'color',
            type: String
        },

        buttonLabel: {
            default: null,
            type: String
        },

        itemName: {
            default: null,
            type: String
        },

        valueName: {
            default: null,
            type: String
        },

        values: {
            default: null,
            type: [ Array ]
        }
    },

    data: () => ({
        optionItems: [],

        tagvalue: null,

        errorValue: null,
        errorName: null,

        selected: {},
        selected_mode: null,
        selected_id: null,
        selected_value: null,
        selected_name: null
    }),

    computed: {
        id() {
            return 'option-picker-' + this._uid;
        },

        isTag() {
            return this.type == 'tags';
        },

        isImage() {
            return this.type == 'image';
        },

        isColor() {
            return this.type == 'color';
        },

        showButton() {
            return this.isColor || this.isImage;
        },

        showInput() {
            return this.isTag;
        },

        modalHeader() {
            let result = '';

            switch (this.selected_mode) {
                case MODE_CREATE:
                    result = 'Add';
                    break;
                case MODE_UPDATE:
                    result = 'Edit';
                    break;
            }

            return result + ' Option';
        }
    },

    watch: {
        selected_name(value) {
            if (value) {
                this.errorName = null;
            }
        },

        values(value) {
            this.optionItems = value;
        }
    },

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            this.optionItems = this.values ? this.values : this.optionItems;
            this.reset();
        },

        open(id = null) {
            this.select(id);
            this.$refs.modal.open();
        },

        close() {
            this.clearErrors();
            this.$refs.modal.close();
        },

        storeTag() {
            if (this.invalid) {
                this.invalid = false;
            }

            if (!this.empty(this.tagvalue)) {
                let id, name;
                id = this.tagvalue.trim().toLowerCase();
                let exists = this.optionItems.filter(obj => {
                    return obj.name.toLowerCase() == id;
                })[0];

                if (!exists) {
                    name = this.tagvalue;

                    this.optionItems.push({
                        id: id,
                        name: name
                    });
                }

                this.tagvalue = '';
            }
        },

        keypress(event) {
            let key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

            if (key == ',') {
                event.preventDefault();
                this.storeTag();
            }
        },

        keydown(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                this.submit();
            }
        },

        select(id) {
            let selected = {};
            this.selected_mode = MODE_CREATE;
            this.selected_id = id;

            if (id) {
                this.selected_mode = MODE_UPDATE;
                selected = this.optionItems.filter(optionItem => {
                    return optionItem.id == id;
                })[0];
            }

            if (selected) {
                this.selected_id = selected.id;
                this.selected_name = selected.name;

                if (!this.isTag) {
                    this.selected_value = selected.value;
                }
            }

            if (!this.selected_value && this.isColor) {
                this.selected_value = '#FFFFFF';
            }

        },

        submit() {
            let value = {};
            this.clearErrors();

            if (!this.selected_name) {
                this.errorName = 'The name field is required';
            }

            if (this.selected_name) {
                value = this.optionItems.filter(optionItem => {
                    return this.lowerCase(optionItem.name) == this.lowerCase(this.selected_name);
                })[0];

                if (value) {
                    if (value.id != this.selected_id) {
                        this.errorName = 'The name has already been taken.';
                    }
                }
            }

            if (!this.isTag) {
                if (!this.selected_value) {
                    if (this.isColor) {
                        this.errorValue = 'The color field is required.';
                    }

                    if (this.isImage) {
                        this.errorValue = 'The image field is required.';
                    }
                }
            }


            if (this.errorName || this.errorValue) {
                return;
            }

            this.save();
            this.reset();
            this.close();
        },

        save() {
            if (this.selected_id) {
                let i = this.arraySearch(this.optionItems, this.selected_id, 'id');
                this.optionItems[i].name = this.selected_name;
                this.optionItems[i].value = this.selected_value;
            } else {
                let count = this.array_count(this.optionItems);
                let id = this.id + '-option_item-' + count;

                this.optionItems.push({
                    id: id,
                    name: this.selected_name,
                    value: this.selected_value
                });
            }
        },

        remove(id) {
            this.optionItems = this.optionItems.filter(color => {
                return color.id != id;
            });
        },

        reset() {
            this.selected_id = null;
            this.selected_name = null;

            if (this.isColor) {
                this.selected_value = '#FFFFFF';
            }

            if (this.isImage) {
                this.selected_value = null;
            }
        },

        clearErrors() {
            this.errorName = null;
            this.errorValue = null;
        },

        selectImage() {
            this.errorValue = null;
            switch (this.selected_mode) {
                case MODE_CREATE:
                    this.selected_value = null;
                    break;
            }
        },

        getImage(event) {
            let files = event.target.files;
            this.getBase64(files[0]).then(file => {
                this.selected_value = file;
            });
        },

        replace(string) {
            if (string) {
                string = string.replace('[', '.');
                string = string.replace(']', '.');
            }

            return string;
        }
    }
};
</script>

<style scoped>
.border-dashed {
	border-style: dashed !important;
	border-width: 3px !important;
}
</style>