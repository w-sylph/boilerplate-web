import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
	modules: {
		searchNavigation: {
			namespaced: true,
			state: {
				visible: false
			},

			mutations: {
				open(state) {
					state.visible = true;
				},

				close(state) {
					state.visible = false;
				},

				toggle(state) {
					state.visible = !state.visible;
				}
			}
		}
	},

	getters: {
		//
	}
});