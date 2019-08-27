<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="dashboard">
            <h3>داشبورد</h3>
            <div class="row">
                <div class="col-md-6 dashboard-col">
                    <div class="col-md-11 dashboard-box">
                        <h3>لیست مشتریان</h3>
                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>اعتبار تعدادی</th>
                                    <th>اعتبار بازه ای</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(customer) in customers">
                                    <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                                    <td class="text-success" v-if="customer.time_charge != null">{{customer.time_charge}}</td>
                                    <td class="text-danger" v-else>ندارد</td>
                                    <td class="text-success" v-if="customer.date_charge != null">{{toJalaali(customer.date_charge,' ','-')}}</td>
                                    <td class="text-danger" v-else>ندارد</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <div class="col-md-6 dashboard-col">
                    <div class="col-md-1"></div>
                    <div class="col-md-11 dashboard-box">
                        <h3>لیست کاربران دمو</h3>
                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>شماره</th>
                                    <th>اعتبار تعدادی</th>
                                    <th>اعتبار بازه ای</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(demoUser) in demoUsers">
                                    <td>{{demoUser.phone_number}}</td>
                                    <td class="text-success" v-if="demoUser.time_charge != null">{{demoUser.time_charge}}</td>
                                    <td class="text-danger" v-else>ندارد</td>
                                    <td class="text-success" v-if="demoUser.date_charge != null">{{toJalaali(demoUser.date_charge,' ','-')}}</td>
                                    <td class="text-danger" v-else>ندارد</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 dashboard-col">
                    <div class="col-md-11 dashboard-box">
                        <h3>لیست اتمام اعتبار ( چک شده)</h3>
                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>توضیحات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(customer) in ncc">
                                    <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                                    <td v-if="customer.end_charge_comment != null">{{customer.end_charge_comment}}</td>
                                    <td v-else>ندارد</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <div class="col-md-6 dashboard-col">
                    <div class="col-md-1"></div>
                    <div class="col-md-11 dashboard-box">
                        <h3>لیست اتمام اعتبار ( چک نشده)</h3>
                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>توضیحات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(customer) in ncu">
                                    <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                                    <td v-if="customer.end_charge_comment != null">{{customer.end_charge_comment}}</td>
                                    <td v-else>ندارد</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "../../layout/element/Loading";
    import axios from 'axios';
    import {mixins} from "../../../mixins";
    export default {
        name: "Home",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                customers:[],
                demoUsers:[],
                ncc:[],
                ncu:[]
            }
        },
        methods:{
            toJalaali: mixins.toJalaaliJustDate,
            err(err){
                this.loadFailed = true;
                this.loading = false;
                if(err.response) {
                    if (err.response.status === 401) {
                        this.$store.state.authCheck = false;
                    }
                }
                return err;
            },
            init(){
                axios.get('api/no_charge?state=2')
                    .then(res => {
                        this.ncu = res.data;
                        axios.get('api/no_charge?state=3')
                            .then(res => {
                                this.ncc = res.data;
                                axios.get('api/demo_user')
                                    .then(res => {
                                        this.demoUsers = res.data;
                                        axios.get('api/customer')
                                            .then(res => {
                                                this.customers = res.data;
                                                this.loading = false;

                                            })
                                            .catch(err => {
                                                this.err(err)
                                            })
                                        ;
                                    })
                                    .catch(err => {
                                        this.err(err)
                                    })
                                ;
                            })
                            .catch(err => {
                                this.err(err)
                            })
                        ;
                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.init();
            },
        },
        created() {
            this.init();
        },
        computed: {
            chs () {
                return this.$store.state.chs;
            }
        },
        watch: {
            chs (state) {
                if(state === true){
                    this.$store.state.chs = false;
                    this.reload();
                }
            }
        }
    }
</script>

<style scoped>

</style>
