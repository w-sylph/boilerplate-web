<template>
    <transition name="fade">
        <div
            v-if="loading"
            class="loader-container"
            :class="absolute ? 'position-absolute' : 'position-fixed'"
        >
            <div
                class="loader loader-element"
                :class="absolute ? 'position-absolute' : 'position-fixed'"
            />
        </div>
    </transition>
</template>

<script>
export default {
    name: 'loader',

    props: {
        loading: {
            default: false,
            type: Boolean
        },

        absolute: {
            default: false,
            type: Boolean
        }
    }
};
</script>