<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div>
                <button type="button" @click="showSide" class="btn btn-info">
                    <img class="menu-icon" src="../../../../image/menu.png" alt="Menu" />
                </button>
            </div>
            <div>
                <select v-if="hasService" class="form-control" @change="serviceChange($event.target.value)">
                    <option v-for="(srv) in services" :value="srv.id">
                        {{srv.name}}
                    </option>
                </select>
            </div>
            <div>
                <router-link to="/my"><span>{{username}}</span></router-link>
                <button @click="logout" style="margin:5px" class="btn btn-secondary" :disabled="sending">{{$t("words.logout")}}
                    <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </nav>
</template>

<script>
    import {mixins} from "../../../mixins";
    import axios from "axios";
    export default {
        name: "Header",
        data(){
            return{
                sending: false,
                username: null,
                services: [],
                service: null
            }
        },
        methods:{
            showSide : mixins.showSide,
            logout(){
                this.sending = true;
                axios.patch('/api/logout')
                    .then(() => {
                        this.resetSession();
                    })
                    .catch(() => {
                        this.sending = false;
                    })
                ;
            },
            resetSession(){
                this.$store.state.authCheck = false;
                this.$store.state.chs = false;
                this.$store.state.crs = false;
                this.$store.state.isAdmin = false;
                this.$store.state.hasService = false;
                window.axios.defaults.headers.common['Authorization'] = null;
                this.sending = false;
                this.$router.replace('/');
            },
            getUser(){
                axios.get('/api/access')
                    .then((res) => {
                        this.username = res.data.name;
                        this.services = res.data.services;
                        this.$store.state.isAdmin = res.data.username === 'admin' && res.data.id === 1;
                        if(_.isObject(this.services[0])){
                            window.axios.defaults.headers.common['serviceid'] = this.services[0].id;
                            this.$store.state.hasService = true;
                        }else {
                            if(this.isAdmin){
                                this.$router.replace('/add-service');
                            }else {
                                this.$router.replace('/my');
                            }
                        }
                    })
                    .catch((err) => {
                        if(err.response) {
                            if (err.response.status === 401) {
                                this.$store.state.authCheck = false;
                            }
                        }
                    })
                ;
            },
            serviceChange(id){
                window.axios.defaults.headers.common['serviceid'] = id;
                if(this.$router.currentRoute.fullPath === '/'){
                    this.$store.state.chs = true;
                }
                this.$router.replace('/');
            }
        },
        created() {
            this.getUser();
        },
        computed: {
            chs () {
                return this.$store.state.chs;
            },
            crs (){
                return this.$store.state.crs;
            },
            hasService (){
                return this.$store.state.hasService;
            },
            isAdmin () {
                return this.$store.state.isAdmin;
            },
            authCheck () {
                return this.$store.state.authCheck;
            }
        },
        watch: {
            crs (state) {
                if(state === true){
                    this.$store.state.chs = false;
                    this.getUser();
                }
            },
            authCheck (state) {
                if(state === false){
                    this.resetSession();
                }
            },
        }
    }
</script>

<style scoped>

</style>
