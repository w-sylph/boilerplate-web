<template>
    <a
        :href="buttonLink"
        :class="[ buttonDisabled, buttonColor ]"
        class="btn btn-sm"
    >
        <i
            v-if="icon"
            :class="icon"
        />
        <span
            v-if="$slots.default"
            class="ml-1"
        >
            <slot />
        </span>
    </a>
</template>

<script>
export default {
    name: 'view-button',

    props: {
        href: {
            default: null,
            type: String
        },

        icon: {
            default: 'fas fa-eye',
            type: String
        },

        color: {
            default: 'btn-primary',
            type: String
        }
    },

    computed: {
        buttonDisabled() {
            return this.href ? '' : 'disabled';
        },

        buttonColor() {
            return this.color;
        },

        buttonLink() {
            return this.href ? this.href : 'javascript:void(0)';
        }
    }
};
</script>