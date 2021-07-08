import { bus } from '../bus.js';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

	props: {
		disabled: {
			type: Boolean,
			default: false
		}
	},

	data: () => ({
		hasFetched: false
	}),

	computed: {
		canFetch() {
			return !this.disabled && this.fetchUrl;
		}
	},

	created() {
		/* list to events to allow initial fetching */
		bus.$on('sync-tables', () => {
			this.hasFetched = false;
		});
	},

	methods: {
		fetchSetup() {
			if (!this.hasFetched) {
				this.hasFetched = true;
				this.fetch();
			}
		},

		sync() {
            bus.$emit('sync-tables');
            this.fetch();
        }
	}
};