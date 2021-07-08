/* eslint-disable vue/require-name-property */
Vue.mixin({
	computed: {
		appName() {
			return process.env.MIX_APP_NAME || 'Laravel';
		}
	}
});