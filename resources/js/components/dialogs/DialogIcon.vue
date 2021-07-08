<template>
    <div>
        <success-icon v-if="type == 'success'" />
        <error-icon v-if="type == 'error'" />
        <warning-icon v-if="type == 'warning'" />
        <info-icon v-if="type == 'info'" />
    </div>
</template>

<script>
import SuccessIcon from '../icons/SuccessIcon.vue';
import ErrorIcon from '../icons/ErrorIcon.vue';
import WarningIcon from '../icons/WarningIcon.vue';
import InfoIcon from '../icons/InfoIcon.vue';

export default {
    name: 'dialog-icon',

    components: {
        SuccessIcon,
        ErrorIcon,
        WarningIcon,
        InfoIcon
    },

    props: {
        type: {
            default: null,
            type: String
        }
    }
};
</script>