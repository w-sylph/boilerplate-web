<template>
    <div
        v-if="nextUrl || prevUrl"
        class="content d-flex justify-content-end"
    >
        <ul class="pagination pagination-sm">
            <li
                class="page-item"
                :class="[ prevState ]"
            >
                <a
                    class="page-link"
                    :href="prevButtonUrl"
                >
                    <i class="fa fa-arrow-left mr-1" />
                    Previous
                </a>
            </li>

            <li
                class="page-item"
                :class="[ nextState ]"
            >
                <a
                    class="page-link"
                    :href="nextButtonUrl"
                >
                    Next
                    <i class="fa fa-arrow-right ml-1" />
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import FetchMixin from '../../mixins/fetch.js';

export default {
    name: 'page-pagination',

    mixins: [ FetchMixin ],

    data() {
        return {
            nextUrl: null,
            prevUrl: null
        };
    },

    computed: {
        nextState() {
            return this.nextUrl ? null : 'disabled';
        },

        prevState() {
            return this.prevUrl ? null : 'disabled';
        },

        nextButtonUrl() {
            return this.nextUrl ? this.nextUrl : 'javascript:void(0)';
        },

        prevButtonUrl() {
            return this.prevUrl ? this.prevUrl : 'javascript:void(0)';
        }
    },
    methods: {
        fetchSuccess(data) {
            this.nextUrl = data.next_page;
            this.prevUrl = data.prev_page;
        }
    }
};
</script>