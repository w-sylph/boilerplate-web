import FormRequest from '../components/forms/FormRequest.vue';
import ActionButton from '../components/buttons/ActionButton.vue';

/* eslint-disable vue/require-name-property */
// @vue/component
export default {

	components: {
		FormRequest,
        ActionButton
	},

	props: {
		submitUrl: {
            default: null,
            type: String
        }
	}
};