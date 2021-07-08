/* eslint-disable vue/require-name-property */
// @vue/component
export default {

	data: () => ({
		loading: false
	}),

	methods: {
		load(value) {
			this.loading = value;
			this.$emit('load', value);
		}
	}
};