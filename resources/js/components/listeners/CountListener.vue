<template>
    <span
        v-show="count && !loading"
        class="badge"
    >{{ count }}</span>
</template>

<script>
import { bus } from '../../bus.js';
import FetchMixin from '../../mixins/fetch.js';

export default {
    name: 'count-listener',

    mixins: [ FetchMixin ],

    props: {
        event: {
            default: null,
            type: String
        },

        fetchUrl: {
            default: null,
            type: String
        },

        disabled: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        count: 0
    }),

    mounted() {
        this.listen();
    },

    methods: {
        listen() {
            if (this.event && !this.disabled) {
                bus.$on(this.event, () => {
                    this.fetch();
                });
            }
        },

        fetchSuccess(data) {
            this.count = data.count;
        }
    }
};
</script>