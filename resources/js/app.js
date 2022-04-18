/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import store from './store'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app', require('./components/App.vue').default);
Vue.component('game', require('./components/Game.vue').default);

Vue.component('anchor-login-btn', require('./components/api/AnchorLoginButton.vue').default);
Vue.component('wax-login-btn', require('./components/api/WaxLoginButton.vue').default);

Vue.component('game-panel', require('./components/elements/game/GamePanel.vue').default);
Vue.component('game-menu', require('./components/elements/game/GameMenu.vue').default);
Vue.component('game-login-modal', require('./components/elements/game/GameLoginModal.vue').default);


Vue.component('user-interface', require('./components/elements/game/interfaces/UserInterface').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

axios.defaults.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content

export const eventBus = new Vue()

export const app = new Vue({
    store,
    el: '#app',
});
