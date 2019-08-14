import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export const store = new Vuex.Store({
    state:{
        authCheck: false,
        chs : false,
        crs : false,
        isAdmin:false,
    }
});
