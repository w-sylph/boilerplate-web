<template>
    <div
        v-if="$slots['header'] || $slots['default'] || $slots['footer']"
        class="card border-0"
    >
        <div
            v-show="$slots['header'] || $slots['header-button']"
            class="card-header border-0 bg-white pb-0"
        >
            <h5
                v-if="$slots['header']"
                class="font-weight-bold m-0 d-inline-block"
            >
                <slot name="header" />
            </h5>
            <div
                v-if="$slots['header-button']"
                class="float-right"
            >
                <slot name="header-button" />
            </div>
        </div>

        <div
            v-show="$slots['default']"
            class="card-body"
        >
            <slot />
        </div>

        <div
            v-show="$slots['footer']"
            class="card-footer border-top-0 px-0 mx-0"
            style="background: #F4F6F9;"
        >
            <div class="float-right">
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'card'
};
</script>

<style scoped>
.card-footer {
  background: #F4F6F9;
}
</style>