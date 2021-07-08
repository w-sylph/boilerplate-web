<template>
    <div class="form-group">
        <button
            class="btn btn-primary"
            type="button"
            @click="openModal"
        >
            Choose from Media Library
        </button>

        <modal v-model="showModal">
            <template #title>
                {{ label }} <small class="text-muted">({{ count }} files)</small>
            </template>

            <template #body>
                <div>
                    <div class="mb-3">
                        <a
                            href="#"
                            @click.prevent="selectNone"
                        >
                            Select None
                        </a>
                    </div>
                    <data-list
                        ref="data-table"
                        :fetch-url="fetchUrl"
                        order-by="created_at"
                        order-desc
                        infinite-scroll
                        max-height="400px"
                        @load="load"
                        @fetch="init"
                    >
                        <template #body="{ items }">
                            <div class="row no-gutters">
                                <template v-for="(item, index) in items">
                                    <div
                                        :key="`item-${index}`"
                                        class="col-4 pb-3 pr-3"
                                    >
                                        <img
                                            class="w-100 media-img"
                                            :class="{ 'active' : inArray(item.id, selectedIds) }"
                                            :src="renderImage(item.path)"
                                            :alt="item.name"
                                            @click="select(item)"
                                        >
                                    </div>
                                </template>
                            </div>
                        </template>
                    </data-list>

                    <span
                        v-if="selectError"
                        class="text-danger d-block text-left"
                    >
                        {{ selectError }}
                    </span>
                </div>
            </template>

            <template #footer>
                <button
                    class="btn btn-danger"
                    type="button"
                    @click="closeModal"
                >
                    Cancel
                </button>

                <button
                    class="btn btn-primary"
                    type="button"
                    @click="confirm"
                >
                    Confirm
                </button>
            </template>
        </modal>

        <input
            v-for="(file, index) in selectedFiles"
            :key="`file-${index}`"
            type="hidden"
            :name="name"
            :value="file.id"
        >
    </div>
</template>

<script>
import LoaderMixin from 'Mixins/loader.js';
import ArrayMixin from 'Mixins/array.js';
import InputMixin from '../inputs/mixin.js';

import Modal from '../dialogs/Modal.vue';
import DataList from '../lists/DataList.vue';

export default {
    name: 'media-library',

    components: {
        Modal,
        DataList
    },

    mixins: [ LoaderMixin, InputMixin, ArrayMixin ],

    props: {
        fetchUrl: {
            default: null,
            type: String
        }
    },

    data: () => ({
        showModal: false,
        allFiles: [],
        selectedIds: [],
        selectedFiles: [],
        selectError: null,
        count: 0
    }),

    computed: {
        getFilePlaceholder() {
            return '/images/file-placeholder.png';
        }
    },

    methods: {
        init(data) {
            this.count = data.pagination.total;
            this.allFiles = this.allFiles.concat(data.items);
        },

        confirm() {
            if (this.array_count(this.selectedIds) > 0) {
                this.selectedFiles = this.allFiles.filter(obj => {
                    return this.inArray(obj.id, this.selectedIds);
                });
            } else {
                this.selectedFiles = [];
            }

            this.$nextTick(() => {
                this.$emit('select', this.selectedFiles);
                this.closeModal();
            });
        },

        select(file) {
            if (this.inArray(file.id, this.selectedIds)) {
                this.selectedIds = this.selectedIds.filter(id => id != file.id);
            } else {
                this.selectedIds.push(file.id);
            }
        },

        openModal() {
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        renderImage(url) {
            let result = this.getFilePlaceholder;

            if (url.match(/\.(jpeg|jpg|gif|png)$/) != null) {
                result = url;
            }

            return result;
        },

        setMedia(files) {
            this.selectedIds = this.array_pluck(files, 'id');
            this.selectedFiles = files;
        },

        selectNone() {
            this.selectedIds = [];
            this.selectedFiles = [];
        }
    }
};
</script>

<style scoped>
.media-img.active {
    border: 2px dashed #6c757d;
}
</style>