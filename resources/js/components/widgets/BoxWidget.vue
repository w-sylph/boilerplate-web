<template>
    <div>
        <div
            class="small-box shadow"
            :class="color"
        >
            <div class="inner">
                <h3>{{ count }}</h3>
                <p>{{ label }}</p>
            </div>
            <div class="icon">
                <i :class="icon" />
            </div>
            <a
                v-if="href"
                :href="href"
                class="small-box-footer"
            >More info <i class="fas fa-arrow-circle-right" /></a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'box-widget',

    props: {
        icon: {
            default: null,
            type: String
        },

        label: {
            default: null,
            type: String
        },

        count: {
            default: null,
            type: [ Number ,String ]
        },

        color: {
            default: null,
            type: String
        },

        href: {
            default: null,
            type: String
        }
    }
};
</script>