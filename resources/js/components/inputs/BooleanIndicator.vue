<template>
    <div class="d-inline-block">
        <template v-if="text">
            <span>{{ modelValue ? 'Yes' : 'No' }}</span>
        </template>
        <template v-else>
            <span
                v-if="modelValue"
                class="fas fa-check text-success"
            />
            <span
                v-if="!modelValue"
                class="fas fa-times text-danger"
            />
        </template>
    </div>
</template>

<script>
export default {
    name: 'boolean-indicator',

    model: {
        prop: 'modelValue',
        event: 'input'
    },

    props: {
        modelValue: {
            default: null,
            type: [ String, Boolean, Number ]
        },

        text: {
            default: false,
            type: Boolean
        }
    }
};
</script>