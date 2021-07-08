/* eslint-disable vue/require-name-property */
// @vue/component
export default {
	methods: {
		toUrlParams(object) {
			let pairs = [];

			for (let prop in object) {
				if (object[prop]) {
					let key, value;

					if (Array.isArray(object[prop])) {
						let index = 0;
						for (let item in object[prop]) {
							key = encodeURIComponent(prop + '[' + index + ']');
							value = encodeURIComponent(item);
							pairs.push(key + "=" + value);
							index++;
						}
					} else {
						key = encodeURIComponent(prop);
						value = encodeURIComponent(object[prop]);

						pairs.push(key + "=" + value);
					}
				}
			}
			let url = "?" + pairs.join("&");
			return url;
		}
	}
};