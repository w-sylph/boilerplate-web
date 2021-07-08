import LoaderMixin from './loader.js';
import Loader from '../components/loaders/Loader.vue';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

    components: {
        Loader
    },

    mixins: [ LoaderMixin ],

    props: {
        fetchUrl: {
            default: null,
            type: String
        },

        autoScroll: {
            default: false,
            type: Boolean
        }
    },

    data() {
        return {
            hasFetched: false
        };
    },

    computed: {
        fetchParams() {
            return {

            };
        },

        canFetch() {
            return this.fetchUrl;
        }
    },
    mounted() {
        if (this.canFetch) {
            this.fetch();
        }
    },

    methods: {
        fetch() {
            if (this.fetchUrl) {
                return new Promise((resolve, reject) => {
                    this.load(true);

                    axios.post(this.fetchUrl, this.fetchParams)
                    .then(response => {
                        this.fireEmitters();
                        this.fetchSuccess(response.data);

                        if (this.autoScroll) {
                            window.scrollTo(0, 0);
                        }

                        resolve();
                    }).catch(error => {
                        console.log(error);
                        reject();
                    }).then(() => {
                        this.hasFetched = true;
                        this.load(false);
                    });
                });
            }
        },

        fireEmitters() {
            // fire events here
        },

        fetchSuccess() {
            // console.log(data);
        }
    }
};