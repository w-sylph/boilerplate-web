<template>
    <div class="form-group">
        <div
            class="input-group"
            :class="small ? 'input-group-sm' : ''"
        >
            <input
                v-model="value"
                type="text"
                :name="name"
                placeholder="Search here..."
                aria-label="Search here..."
                class="form-control"
                @keydown.enter="search"
            >
            <div class="input-group-append">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="search"
                >
                    <i class="fa fa-search" />
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'search-form',

    props: {
        small: {
            default: false,
            type: Boolean
        },

        name: {
            type: String,
            default: 'search'
        }
    },

    data() {
        return {
            value: null
        };
    },

    methods: {
        search(event) {
            if (event.which == 13 || event.keyCode == 13) {
                event.preventDefault();
                event.stopPropagation();
                this.$emit('search', this.value);
            }
        }
    }
};
</script>