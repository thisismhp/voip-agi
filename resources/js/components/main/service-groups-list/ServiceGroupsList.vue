<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="service-group-list">
            <h3>لیست گروه های سرویس</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>ضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(serviceGroup, index) in serviceGroups">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="`/service-group/${serviceGroup.id}`">{{serviceGroup.name}}</router-link></td>
                        <td v-if="serviceGroup.is_active" class="text-success">فعال</td>
                        <td v-else class="text-danger">غیرفعال</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "../../layout/element/Loading";
    export default {
        name: "ServiceGroupsList",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                serviceGroups:[]
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
                this.getServiceGroups();
            },
            getServiceGroups(){
                axios.get('/api/service_group')
                    .then(res => {
                        this.serviceGroups = res.data;
                        this.loading = false;

                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            }
        },
        created() {
            this.getServiceGroups();
        }
    }
</script>

<style scoped>

</style>
