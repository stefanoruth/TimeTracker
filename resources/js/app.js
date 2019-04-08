/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

import Vue from 'vue'
import VueRouter from 'vue-router'
import route from '../../vendor/tightenco/ziggy/src/js/route'
window.Vue = Vue

Vue.use(VueRouter)
Vue.config.productionTip = false

import App from './views/App'
import Home from './views/Home'

Vue.mixin({
	methods: {
		route: route,
	},
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if (document.getElementById('app')) {
	const app = new Vue({
		el: '#app',
		components: { App },
		router: new VueRouter({
			mode: 'history',
			routes: [
				{
					path: '/',
					component: Home,
				},
			],
		}),
	})
}
