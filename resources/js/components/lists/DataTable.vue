<template>
    <div>
        <div
            ref="container"
            class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm mb-3"
            @mousedown="mousedown"
            @mouseup="mouseup"
            @mousemove="mousemove"
            @mouseleave="mouseleave"
        >
            <table
                class="table text-center"
                :class="[ tableClass ]"
            >
                <!-- Header Slot -->
                <slot name="header">
                    <thead>
                        <tr>
                            <th
                                v-if="showSelect"
                                class="align-middle"
                            >
                                <checkbox
                                    v-model="selected"
                                    class="w-100 p-0 m-0"
                                    name="selected_ids"
                                />
                            </th>
                            <th
                                v-for="(header, index) in headers"
                                :key="`th-${index}`"
                                class="align-middle"
                                @click="sortBy(header.value)"
                            >
                                <span class="d-inline-block theme--text">{{ header.text }}</span>
                                <span
                                    v-if="header.value && !sortable"
                                    class="ml-2 d-inline-block theme--text"
                                    :class="sortIcon(header.value)"
                                />
                            </th>
                            <th
                                v-if="!noAction"
                                class="align-middle"
                            >
                                {{ actionText }}
                            </th>
                        </tr>
                    </thead>
                </slot>

                <!-- Body Slot -->
                <tbody
                    ref="sortable"
                    class="theme--text"
                >
                    <slot
                        name="body"
                        :items="items"
                    />

                    <!-- Empty list -->
                    <tr v-if="!array_count(items) && !loading">
                        <td :colspan="colspan">
                            {{ emptyText }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- List Pagination -->
        <div v-if="!empty(pagination)">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <div
                        v-if="pagination.total > 0"
                        class="d-flex align-items-center form-inline"
                    >
                        <label class="d-inline-block theme--text">Show</label>
                        <select
                            v-model="limit"
                            class="form-control d-inline-block w-auto mx-2"
                        >
                            <option
                                v-for="(limit, index) in limits"
                                :key="`limit-${index}`"
                                :value="limit"
                            >
                                {{ limit }}
                            </option>
                        </select>
                        <label class="d-inline-block theme--text">Entries</label>
                    </div>
                </div>
                <div class="col-12 col-md-6 text-sm-left text-md-right">
                    <pagination
                        v-model="page"
                        :total-visible="totalVisible"
                        :length="pagination.last_page"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 d-flex align-items-center">
                    <p
                        v-if="pagination.total > 0"
                        class="mb-0 theme--text"
                    >
                        Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }}
                    </p>
                </div>
                <div class="col-12 col-md-6 text-sm-left text-md-right">
                    <div
                        v-if="pagination.last_page > 1"
                        class="d-inline-flex form-inline"
                    >
                        <label>Go to Page:</label>
                        <select
                            v-model="page"
                            class="form-control ml-2"
                        >
                            <option
                                v-for="(page_number, index) in pagination.last_page"
                                :key="`page-number-${index}`"
                                :value="page_number"
                            >
                                {{ page_number }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ListMixin from './mixin.js';
import Checkbox from 'Components/inputs/Checkbox.vue';

export default {
    name: 'data-table',

    components: {
        'checkbox': Checkbox
    },

    mixins: [ ListMixin ],

    props: {
        borderless: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        down: false,
        scrollLeft: 0,
        center: 0
    }),

    computed: {
        tableClass() {
            let	result = 'table-hover table-striped table-bordered';

            if (this.borderless) {
                result = 'table-borderless';
            }

            return result;
        }
    },

    methods: {
        mousedown(event) {
            this.down = true;
            this.scrollLeft = this.$refs.container.scrollLeft;
            this.center = event.clientX;
        },

        mouseup() {
            this.down = false;
        },

        mousemove(event) {
            if (this.down) {
                this.$refs.container.scrollLeft = this.scrollLeft + this.center - event.clientX;
            }
        },

        mouseleave() {
            this.down = false;
        }

        // mousewheel(event) {
        // 	event = window.event || event;
        //        let delta = Math.max(-1, Math.min(1, (event.wheelDelta || -event.detail)));
        //        this.$refs.container.scrollLeft -= (delta*40); // Multiplied by 40
        //        event.preventDefault();
        // },
    }
};
</script>