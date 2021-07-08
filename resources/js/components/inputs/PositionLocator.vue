<template>
    <div class="col-sm-12">
        <action-button
            v-if="fetchUrl"
            class="mb-2"
            :small="small"
            color="btn-warning"
            icon="fas fa-map-pin"
            confirm-dialog
            :disabled="loading"
            title="Fetch Position"
            message="Are you sure you want to fetch position base on your address?"
            @load="load"
            @success="fetch"
        >
            Fetch Position
        </action-button>

        <div class="row">
            <div class="form-group col-sm-12 col-md-6">
                <label>Latitude</label>
                <input
                    v-model="item.latitude"
                    type="text"
                    name="latitude"
                    class="form-control"
                    @input="inputChange"
                >
            </div>

            <div class="form-group col-sm-12 col-md-6">
                <label>Longitude</label>
                <input
                    :value="item.longitude"
                    type="text"
                    name="longitude"
                    class="form-control"
                    @input="inputChange"
                >
            </div>

            <div class="col-sm-12">
                <small>
                    Click
                    <a
                        href="https://www.wikihow.com/Get-Latitude-and-Longitude-from-Google-Maps"
                        target="_blank"
                    >here</a>
                    to find out how to manually get your latitude and longitude
                </small>
            </div>
        </div>
    </div>
</template>

<script>
import LoaderMixin from '../loaders/mixin.js';
import ResponseMixin from '../../mixins/response.js';

import ActionButton from '../buttons/ActionButton.vue';

export default {
    name: 'position-locator',

    components: {
        ActionButton
    },

    mixins: [ LoaderMixin, ResponseMixin ],

    props: {
        modelValue: {
            default: null,
            type: Object
        },

        address: {
            default: null,
            type: String
        },

        latitude: {
            default: null,
            type: [ String, Number ]
        },

        longitude: {
            default: null,
            type: [ String, Number ]
        },

        fetchUrl: {
            default: null,
            type: String
        },

        small: {
            default: false,
            type: Boolean
        }
    },

    data: () => ({
        item: {}
    }),

    watch: {
        address(value) {
            this.item.address = value;
        },

        latitude(value) {
            this.item.latitude = value;
        },

        longitude(value) {
            this.item.longitude = value;
        }
    },

    methods: {
        fetch() {
            if (this.loading) {
                return;
            }

            let params = {
                address: this.address
            };

            this.load(true);

            axios.post(this.fetchUrl, params)
                .then(response => {
                    const data = response.data;
                    this.parseSuccess(data.message);
                    this.item.latitude = data.latitude;
                    this.item.longitude = data.longitude;

                    this.inputChange();
                }).catch(error => {
                    this.parseError(error);
                }).then(() => {
                    this.load(false);
                });
        },

        inputChange() {
            this.$emit('input', {
                latitude: this.item.latitude,
                longitude: this.item.longitude
            });
        }
    }
};
</script>