<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button type="button" @click="showSide" class="btn btn-info">
                <img class="menu-icon" src="../../../../icon/menu.png"  alt="Menu" />
            </button>
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
                username: null
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
                    })
                    .catch((err) => {
                        if(err.response) {
                            if (err.response.status === 401) {
                                this.$store.state.authCheck = false;
                            }
                        }
                    })
                ;
            }
        },
        created() {
            this.getUser();
        }
    }
</script>

<style scoped>

</style>
