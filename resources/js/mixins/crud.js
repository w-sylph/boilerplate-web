import { bus } from '../bus.js';
import FetchMixin from './fetch.js';
import FormMixin from './form.js';

import Card from '../components/containers/Card.vue';
import Divider from '../components/containers/Divider.vue';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

	components: {
        Card,
        Divider
	},

	mixins: [ FetchMixin, FormMixin ],

	props: {
		fetchUrl: {
			default: null,
			type: String
		},

		sortUrl: {
			default: null,
			type: String
		}
	},

	data() {
		return {
			item: {}
		};
	},

	methods: {
		/* Sync table when crud is fetched to update any related items */
		fireEmitters() {
            bus.$emit('sync-tables');
        }
	}
};