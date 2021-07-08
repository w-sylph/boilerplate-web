/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap.js';
import './script.js';
import './mixins/global/index.js';

import VuejsDialog from 'vuejs-dialog';
Vue.use(VuejsDialog);

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

// import money from 'v-money';
// Vue.use(money, {precision: 4})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('count-listener', require('./components/listeners/CountListener.vue').default);

Vue.component('page-pagination', require('./components/paginations/PagePagination.vue').default);

Vue.component('change-password-form', require('./components/forms/ChangePasswordForm.vue').default);

Vue.component('dialog-container', require('./components/dialogs/DialogContainer.vue').default);

Vue.component('sandbox', require('./views/sandbox/Sandbox.vue').default);

import './views/developer/index.js';
import './views/admin/index.js';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import SetupMixin from './mixins/setup.js';

const app = {
	init() {
		this.setup();
		this.setupNavPills();
	},

	setupNavPills() {
		$('.nav--hashable a').click(function(e) {
			e.preventDefault();
			$(this).tab('show');
		});

		// store the currently selected tab in the hash value
		$('.nav--hashable > li > a').on('shown.bs.tab', function(e) {
			var id = $(e.target).attr("href").substr(1);
			window.location.hash = id;
		});

		// on load of the page: switch to the currently selected tab
		var hash = window.location.hash;
		$('.nav--hashable a[href="' + hash + '"]').tab('show');
	},

	setup() {
		/* eslint-disable vue/require-name-property */
		new Vue({
			el: '#app',

			mixins: [ SetupMixin ]
		});
	}
};

app.init();
