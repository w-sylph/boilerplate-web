/* eslint-disable vue/require-name-property */
// @vue/component
export default {
	methods: {
		initList(component) {
			let element = this.$refs[component];

			if (element) {
				if (!element.hasFetched) {
					element.fetchSetup();
				}
			}
		}
	}
};