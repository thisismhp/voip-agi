import VueRouter from 'vue-router'
import App from './components/App'
import {routes} from "./routes";

require('./bootstrap');
window.Vue = require('vue');
Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

new Vue({
    el: '#app',
    components: { App },
    render: h => h(App),
    router,
});