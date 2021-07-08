<template>
    <div>
        <!-- Body Slot -->

        <!-- Infinite Scroll Body -->
        <div
            v-if="infiniteScroll"
            :style="'max-height:' + maxHeight + '; overflow-y: auto;'"
            @scroll="scroll"
        >
            <slot
                name="body"
                :items="items"
            />

            <div
                v-if="loading && infiniteScroll"
                class="text-center theme--text"
            >
                <p>Loading...</p>
            </div>

            <div
                v-if="array_count(items) && infiniteScroll && isLastPage"
                class="text-center theme--text"
            >
                <p>{{ endText }}</p>
            </div>
        </div>

        <!-- Pagination Body -->
        <template v-else>
            <slot
                name="body"
                :items="items"
            />
        </template>

        <!-- Empty list -->
        <div class="text-center mt-3">
            <p
                v-if="!array_count(items) && !loading"
                class="theme--text"
            >
                {{ emptyText }}
            </p>
        </div>

        <!-- List Pagination -->
        <div v-if="! empty(pagination) && array_count(items) > 0 && ! infiniteScroll">
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
                        <label class="theme--text">Go to Page:</label>
                        <select
                            v-model="page"
                            class="form-control ml-2"
                        >
                            <option
                                v-for="(page_number, index) in pagination.last_page"
                                :key="`page-number-${index}`"
                                class="theme--text"
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

export default {
    name: 'data-list',
    mixins: [ ListMixin ]
};
</script>