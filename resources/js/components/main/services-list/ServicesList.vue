<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="service-list">
            <h3>لیست سرویس ها</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(service, index) in services">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="`/service/${service.id}`">{{service.name}}</router-link></td>
<!--                        <td><router-link :to="'/customer/'+ service.id">{{service.name}}</router-link></td>-->
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "../../layout/element/Loading";
    import axios from "axios";
    export default {
        name: "ServicesList",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                services:[]
            }
        },
        methods:{
            err(err){
                this.loadFailed = true;
                this.loading = false;
                if(err.response.status === 401){
                    this.$store.state.authCheck = false;
                }
                return err;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.getServices();
            },
            getServices(){
                axios.get('api/service')
                    .then(res => {
                        this.services = res.data;
                        this.loading = false;

                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            }
        },
        created() {
            this.getServices();
        }
    }
</script>

<style scoped>

</style>
