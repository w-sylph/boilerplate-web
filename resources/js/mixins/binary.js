/* eslint-disable vue/require-name-property */
// @vue/component
export default {
	methods: {
		getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }
	}
};