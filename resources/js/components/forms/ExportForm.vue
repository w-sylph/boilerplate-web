<template>
    <div class="d-inline-block">
        <form
            :action="exportUrl"
            method="POST"
            @submit.prevent="submit"
        >
            <input
                v-if="token"
                v-model="token"
                type="hidden"
                name="_token"
            >
            <button
                type="submit"
                class="btn btn-warning"
                :disabled="loading"
            >
                <i class="fas fa-download mr-2" /> Export
            </button>
        </form>
    </div>
</template>

<script>
import ResponseMixin from 'Mixins/response.js';
import LoaderMixin from 'Mixins/loader.js';
import UrlMixin from 'Mixins/url.js';

export default {
    name: 'export-form',

    mixins: [ ResponseMixin, LoaderMixin, UrlMixin ],

    props: {
        params: {
            default: null,
            type: Object
        },

        submitUrl: {
            default: null,
            type: String
        }
    },

    data: () => ({
        token: null,
        exportUrl: null
    }),

    mounted() {
        this.setup();
    },

    methods: {
        setup() {
            /* Get CSRF Token */
            let token = document.head.querySelector('meta[name="csrf-token"]');

            if (token) {
                this.token = token.content;
            } else {
                console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
            }
        },

        submit(event) {
            if (this.loading) {
                return;
            }

            let object = Object.assign({}, this.params);
            delete object.page;
            delete object.per_page;

            this.exportUrl = this.submitUrl + this.toUrlParams(object);

            this.load(true);

            axios.post(this.exportUrl)
                .then(response => {
                    let data = response.data;
                    this.parseSuccess(data.message);

                    setTimeout(() => {
                        event.target.submit();
                    }, 500);
                }).catch(error => {
                    this.parseError(error);
                }).then(() => {
                    this.load(false);
                });
        }
    }
};
</script>