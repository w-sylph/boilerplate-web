<template>
    <div class="row mb-2">
        <!-- FILTERS -->
        <div class="col-12 col-md-8">
            <div class="row">
                <div class="col-sm-12 form-inline">
                    <button
                        v-if="!hideRefresh"
                        type="button"
                        class="btn btn-light border mr-2"
                        @click="refresh"
                    >
                        <i class="fas fa-sync-alt" />
                    </button>
                    <slot name="left" />
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">
            <div class="row">
                <div class="col-12 justify-content-end form-inline">
                    <slot name="right" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'filter-box',

    props: {

        hideRefresh: {
            default: false,
            type: Boolean
        }

    },

    methods: {

        refresh() {
            this.$emit('refresh');
        }

    }

};
</script>