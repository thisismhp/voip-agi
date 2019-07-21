import App from './components/App'
require('./bootstrap');
window.Vue = require('vue');

new Vue({
    el: '#app',
    components: { App },
    render: h => h(App),

});