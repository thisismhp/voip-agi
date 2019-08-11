<template>
    <div id="app">
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">{{$t("words.retry")}}</button>
        </div>
        <Auth v-else-if="!authCheck"/>
        <Layout v-else-if="authCheck"/>
    </div>
</template>

<script>
    import Layout from "./layout/Layout";
    import Auth from "./layout/auth/Auth";
    import Loading from "./layout/element/Loading";
    export default {
        name: "App",
        components: {Loading, Auth, Layout},
        data(){
            return{
                loading:true,
                loadFailed:false,
            }
        },
        computed:{
            authCheck(){return this.$store.state.authCheck}
        },
        methods:{
            init(){
                axios.get('/api/access')
                    .then((res) => {
                        let services = res.data.services;
                        if(_.isObject(services[0])){
                            window.axios.defaults.headers.common['serviceid'] = services[0].id;
                        }
                        this.loading = false;
                        this.loadFailed = false;
                        this.$store.state.authCheck = true;
                    })
                    .catch((err) => {
                        this.loading = false;
                        if(err.response) {
                            if (err.response.status === 401) {
                                this.$store.state.authCheck = false;
                            }else{
                                this.loadFailed = true;
                            }
                        }else {
                            this.loadFailed = true;
                        }
                    })
                ;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.init();
            }
        },
        created() {
            this.init();
        }
    }
</script>

<style scoped>

</style>
