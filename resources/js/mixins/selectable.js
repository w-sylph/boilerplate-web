import ArrayMixin from './array.js';
import LoaderMixin from './loader.js';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

	mixins: [ ArrayMixin, LoaderMixin ],

	props: {
		selectUrl: {
			default: null,
			type: String
		}
	},

	data: () => ({
        selected_ids: []
    }),

	watch: {
		selected_ids(value) {
			if (this.array_count(value) < 1) {
				this.unselectAll();
			}
		}
	},

	methods: {
		setItemIds(data) {
			if (data.value) {
				this.selected_ids = data.ids;
				// this.load(true);
				// axios.post(this.selectUrl, data.params)
				// .then(response => {
				// 	let data = response.data;
				// 	this.selected_ids = data.items;
				// }).catch(error => {

				// }).then(() => {
				// 	this.load(false);
				// });
			} else {
				this.selected_ids = [];
			}
		},

		unselectAll() {
			this.$refs['data-table'].selected = false;
		}
	}
};