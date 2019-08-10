<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div>
                <button type="button" @click="showSide" class="btn btn-info">
                    <img class="menu-icon" src="../../../../image/menu.png" alt="Menu" />
                </button>
            </div>
            <div>
                <select class="form-control" @change="serviceChange($event.target.value)">
                    <option v-for="(srv) in services" :value="srv.id">
                        {{srv.name}}
                    </option>
                </select>
            </div>
            <div>
                <span>{{username}}</span>
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
                        this.$store.state.authCheck = false;
                        window.axios.defaults.headers.common['Authorization'] = null;
                        this.sending = false;
                    })
                    .catch(() => {
                        this.sending = false;
                    })
                ;
            },
            getUser(){
                axios.get('/api/access')
                    .then((res) => {
                        this.username = res.data.name;
                        this.services = res.data.services;
                        this.$store.state.isAdmin = res.data.username === 'admin' && res.data.id === 1;
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
            }
        },
    }
</script>

<style scoped>

</style>
