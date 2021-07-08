<template>
    <div class="row">
        <div
            class="col-12 order-2 order-md-1"
            :class="mainDivClass"
        >
            <form-request
                :submit-url="submitUrl"
                confirm-dialog
                sync-on-success
                validate
                @load="load"
                @success="fetch"
                @error="setErrors"
            >
                <card>
                    <template #header>
                        Basic Information
                    </template>

                    <div class="row">
                        <text-field
                            v-model="item.name"
                            required
                            class="col-12"
                            label="Name"
                            name="name"
                        />

                        <text-area
                            v-model="item.description"
                            required
                            class="col-12"
                            name="description"
                            label="Description"
                        />

                        <text-editor
                            v-model="item.description"
                            required
                            class="col-12"
                            label="Description"
                            name="description"
                            row="5"
                        />

                        <selector
                            v-model="item.status"
                            required
                            class="col-12"
                            name="status"
                            label="Status"
                            :items="statuses"
                            placeholder="Please select a status"
                        />

                        <div
                            v-if="item.reason"
                            class="form-group col-12"
                        >
                            <label>Reason</label>
                            <textarea
                                v-model="item.reason"
                                required
                                name="reason"
                                placeholder="Reason"
                                class="form-control"
                                readonly
                            />
                        </div>

                        <radio-list
                            v-model="item.user_id"
                            required
                            class="col-12"
                            name="user"
                            label="User"
                            :items="users"
                            item-value="id"
                            item-text="name"
                        />

                        <selector
                            v-model="item.user_id"
                            required
                            class="col-12"
                            name="user_id"
                            label="User"
                            :items="users"
                            item-value="id"
                            item-text="name"
                            empty-text="None"
                            placeholder="Please select user"
                        />

                        <div class="form-group col-12">
                            <label>Users</label>
                            <checkbox
                                v-for="(user, index) in users"
                                :key="`item-${index}`"
                                v-model="item.data"
                                required
                                class="w-100 py-0 my-0"
                                name="user_ids[]"
                                :label="user.name"
                                :value="user.id"
                            />
                        </div>

                        <selector
                            v-model="item.data"
                            required
                            class="col-12"
                            name="data[]"
                            label="Data"
                            :items="users"
                            item-value="id"
                            item-text="name"
                            multiple
                            taggable
                            placeholder="Please select an item"
                            :disabled="loading"
                            :error="getError('data')"
                        />

                        <selector
                            v-model="item.tag_ids"
                            required
                            class="col-12"
                            name="tags[]"
                            label="Tags"
                            :items="tags"
                            item-value="id"
                            item-text="name"
                            multiple
                            taggable
                            placeholder="Please select tags"
                            :disabled="loading"
                            :error="getError('tags')"
                        />

                        <file-picker
                            :value="item.path"
                            class="col-12 mt-2"
                            label="Image"
                            name="file_path"
                            type="image"
                            placeholder="Choose a File"
                        />

                        <file-picker
                            :value="images"
                            class="col-12 mt-2"
                            label="Images"
                            name="images[]"
                            placeholder="Choose Files"
                            type="image"
                            multiple
                            :remove-url="item.removeImageUrl"
                            sortable
                            :sort-url="sortUrl"
                            :error="getError('images')"
                        />

                        <file-dropzone
                            class="col-12"
                            label="Files"
                            name="files[]"
                            type="file"
                            store-type="file"
                            :files="images"
                            :remove-url="item.removeImageUrl"
                            sortable
                            :sort-url="sortUrl"
                            :error="getError('files')"
                            :media-url="mediaUrl"
                            @load="load"
                        />

                        <date-picker
                            v-model="item.date"
                            required
                            class="form-group col-12 col-md-6 mt-2"
                            label="Date"
                            name="date"
                            placeholder="Choose a date"
                        />

                        <date-picker
                            v-model="item.dates"
                            required
                            class="form-group col-12 col-md-6 mt-2"
                            label="Dates"
                            name="dates[]"
                            mode="multiple"
                            placeholder="Choose dates"
                            :error="getError('dates')"
                        />

                        <birthday-picker
                            v-model="item.date"
                            required
                            class="form-group col-12 "
                            label="Birthday"
                            name="birthday"
                        />

                        <color-picker
                            v-model="item.color"
                            required
                            class="col-12"
                            type="hidden"
                            label="Color"
                            name="color"
                            use-as-button
                            show-always
                            inline
                        />

                        <number-field
                            v-model="item.order_column"
                            required
                            class="col-12"
                            label="Order"
                            name="order_column"
                        />

                        <contact-number
                            v-model="item.mobile_number"
                            required
                            class="col-12"
                            type="mobile"
                            name="mobile_number"
                            label="Mobile Number"
                            area-code="63"
                        />

                        <contact-number
                            v-model="item.telephone_number"
                            required
                            class="col-12"
                            type="telephone"
                            name="telephone_number"
                            label="Telephone Number"
                        />

                        <zip-code
                            v-model="item.zip_code"
                            required
                            class="col-12"
                            label="Zip code"
                            name="zip_code"
                        />
                    </div>

                    <template #footer>
                        <action-button
                            type="submit"
                            :disabled="loading"
                            class="btn-primary"
                        >
                            Save Changes
                        </action-button>

                        <action-button
                            v-if="item.archiveUrl && item.restoreUrl"
                            color="btn-danger"
                            alt-color="btn-warning"
                            :action-url="item.archiveUrl"
                            :alt-action-url="item.restoreUrl"
                            label="Archive"
                            alt-label="Restore"
                            :show-alt="item.trashed"
                            confirm-dialog
                            title="Archive item"
                            alt-title="Restore item"
                            :message="'Are you sure you want to archive ' + item.name + '?'"
                            :alt-message="'Are you sure you want to restore ' + item.name + '?'"
                            :disabled="loading"
                            @load="load"
                            @success="fetch"
                            @error="fetch"
                        />
                    </template>
                </card>
            </form-request>
        </div>

        <div
            v-if="item.id"
            class="col-12 col-md-4 order-1 order-md-2"
        >
            <card>
                <template #header>
                    Status
                </template>
                <div class="row mb-3">
                    <div class="col-12">
                        <p><span class="font-weight-bold">Current status:</span> {{ item.status_label }}</p>
                    </div>
                    <div class="col-12">
                        <sample-item-buttons
                            :item="item"
                            @load="load"
                            @fetch="update"
                        />
                    </div>
                </div>
            </card>
        </div>

        <loader :loading="loading" />
    </div>
</template>

<script>
import { bus } from 'App/bus.js';
import CrudMixin from 'Mixins/crud.js';
import ErrorMixin from 'Mixins/error.js';

import ActionButton from 'Components/buttons/ActionButton.vue';

import BirthdayPicker from 'Components/inputs/BirthdayPicker.vue';
import DatePicker from 'Components/inputs/DatePicker.vue';
import NumberField from 'Components/inputs/NumberField.vue';
import ColorPicker from 'Components/inputs/ColorPicker.vue';
import ContactNumber from 'Components/inputs/ContactNumber.vue';
import RadioList from 'Components/inputs/RadioList.vue';
import Selector from 'Components/inputs/Selector.vue';
import FilePicker from 'Components/inputs/FilePicker.vue';
import TextField from 'Components/inputs/TextField.vue';
import TextEditor from 'Components/inputs/TextEditor.vue';
import TextArea from 'Components/inputs/TextArea.vue';
import ZipCode from 'Components/inputs/ZipCode.vue';
import Checkbox from 'Components/inputs/Checkbox.vue';
import FileDropzone from 'Components/inputs/FileDropzone.vue';

import SampleItemButtons from './SampleItemButtons.vue';

export default {
    name: 'sample-item-view',

    components: {
        ActionButton,
        TextField,
        ColorPicker,
        ContactNumber,
        NumberField,
        Selector,
        FilePicker,
        TextEditor,
        TextArea,
        BirthdayPicker,
        DatePicker,
        ZipCode,
        Checkbox,
        RadioList,
        FileDropzone,
        SampleItemButtons
    },

    mixins: [ CrudMixin, ErrorMixin ],

    props: {
        mediaUrl: {
            default: null,
            type: String
        }
    },

    data: () => ({
        item: {
            data: []
        },
        users: [],
        statuses: [],
        images: [],
        tags: [],

        hasFetched: false
    }),

    computed: {
        mainDivClass() {
            return this.item.id ? 'col-md-8' : '';
        }
    },

    mounted() {
        this.listen();
    },

    methods: {
        fetchSuccess(data) {
            this.users = this.users ? data.users : this.users;
            this.statuses = data.statuses ? data.statuses : this.statuses;
            this.images = data.images ? data.images : this.images;
            this.tags = data.tags ? data.tags : this.tags;

            setTimeout(() => {
                this.item = data.item ? data.item : this.item;
            }, 10);
        },

        update() {
            this.fetch();
            bus.$emit('update-sample-item-count');
        },

        listen() {
            if (process.env.MIX_PUSHER_APP_ENABLED) {

                // Echo.channel('admin')
                // .listen('.sample.changed', (data) => {
                // 	if (data.id == this.item.id) {
                //      this.update();
                // 	}
                // });
            }
        }
    }
};
</script>