<template>
    <Loading v-if="loading" />
    <div class="failed center-align" v-else-if="loadFailed">
        <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
    </div>
    <div v-else-if="!loading">
        <div id="demo-users-list">
            <h3>لیست کاربران دمو</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>شماره</th>
                        <th>اعتبار تعدادی</th>
                        <th>اعتبار بازه ای</th>
                        <th>افزایش اعتبار</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(demoUser, index) in demoUsers">
                        <td>{{index + 1}}</td>
                        <td>{{demoUser.phone_number}}</td>
                        <td class="text-success" v-if="demoUser.time_charge != null">{{demoUser.time_charge}}</td>
                        <td class="text-danger" v-else>ندارد</td>
                        <td class="text-success" v-if="demoUser.date_charge != null">{{toJalaali(demoUser.date_charge,' ','-')}}</td>
                        <td class="text-danger" v-else>ندارد</td>
                        <td><router-link class="btn btn-secondary" :to="`/charge-one-demo-user?id=${demoUser.id}`" >افزایش اعتبار</router-link></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perpage" align="center" :disabled="changing"></b-pagination>
    </div>
</template>

<script>
    import axios from "axios";
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";

    export default {
        name: "DemoUsersList",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                demoUsers:[],
                rows:1,
                currentPage:1,
                perpage:1,
                changing:false,
                isFirstTime:true
            }
        },
        methods:{
            toJalaali : mixins.toJalaaliJustDate,
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
                this.getDemoUsers();
            },
            getDemoUsers(page){
                axios.get(`api/demo_user?page=${page}`)
                    .then(res => {
                        this.demoUsers = res.data.data;
                        this.perpage = res.data.per_page;
                        this.rows = res.data.total;
                        this.loading = false;
                        this.changing = false;
                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            }
        },
        watch:{
            currentPage(val){
                if(!this.isFirstTime) {
                    this.changing = true;
                    this.getDemoUsers(val);
                    this.$router.push({path: 'demo-users-list', query: {page: (val == null) ? 1 : val}})
                }else{
                    this.isFirstTime = false;
                }
            }
        },
        created() {
            let page = this.$route.query.page;
            if(_.isString(page) || _.isInteger(page)){
                this.currentPage = page;
            }else{
                this.currentPage = 1;
                this.isFirstTime = false;
            }
            this.getDemoUsers(this.currentPage);
        }
    }
</script>

<style scoped>

</style>
