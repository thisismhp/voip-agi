import VueRouter from 'vue-router'
import Vuex from 'vuex'
import App from './components/App'
import {routes} from "./routes";
import {store} from "./store";

require('./bootstrap');
window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(Vuex);

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

new Vue({
    el: '#app',
    components: { App },
    render: h => h(App),
    router,
    store
});
