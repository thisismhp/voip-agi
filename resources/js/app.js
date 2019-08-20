import VueRouter from 'vue-router'
import Vuex from 'vuex'
import App from './components/App'
import {routes} from "./routes";
import {store} from "./store";
import VueI18n from 'vue-i18n';
import {messages} from "./messages";
import BootstrapVue from "bootstrap-vue";

require('./bootstrap');

window.Vue = require('vue');
Vue.use(BootstrapVue);
Vue.use(VueRouter);
Vue.use(VueI18n);
Vue.use(Vuex);

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

const i18n = new VueI18n({
    locale: 'fa',
    messages,
});

new Vue({
    el: '#app',
    components: { App },
    render: h => h(App),
    router,
    store,
    i18n
});
