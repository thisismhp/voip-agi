<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="customer-list">
            <h3>لیست مشتریان</h3>
            <div>
                <div class="form-row border-bottom">
                    <div class="form-group col-md-12">
                        <input type="text" id="search" class="form-control" placeholder="جست و جو" @input="filterCustomers = filter($event.target.value)"/>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>اعتبار تعدادی</th>
                        <th>اعتبار بازه ای</th>
                        <th>وضعیت</th>
                        <th>افزایش اعتبار</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(customer, index) in filterCustomers">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                        <td class="text-success" v-if="customer.time_charge != null">{{customer.time_charge}}</td>
                        <td class="text-danger" v-else>ندارد</td>
                        <td class="text-success" v-if="customer.date_charge != null">{{toJalaali(customer.date_charge,' ','-')}}</td>
                        <td class="text-danger" v-else>ندارد</td>
                        <td v-if="customer.is_active" class="text-success">فعال</td>
                        <td v-else class="text-danger">غیرفعال</td>
                        <td><router-link class="btn btn-secondary" :to="`/charge-one-customer?id=${customer.id}`" >افزایش اعتبار</router-link></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perpage" align="center" :disabled="changing"></b-pagination>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";
    export default {
        name: "CustomersList",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                customers:[],
                filterCustomers:[],
                rows:1,
                currentPage:1,
                perpage:1,
                changing:false,
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
                this.getCustomers();
            },
            getCustomers(page){
                axios.get(`api/customer?page=${page}`)
                    .then(res => {
                        this.customers = res.data.data;
                        this.filterCustomers = this.customers;
                        this.perpage = res.data.per_page;
                        this.rows = res.data.total;
                        this.loading = false;
                        this.changing = false;
                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            },
            filter(input){
                const customers = this.customers;
                let exp = [];
                if(input === null || input === ''){
                    exp = customers;
                }else {
                    for(let item in customers){
                        if (!customers.hasOwnProperty(item)) continue;
                        if(customers[item].name.search(input) > -1){
                            exp.push(customers[item]);
                        }
                    }
                }
                return exp;
            }
        },
        watch:{
            currentPage(val){
                this.changing = true;
                this.getCustomers(val);
                this.$router.push({ path: 'customer-list', query: { page: (val == null)?1:val }})
            }
        },
        created() {
            let page = this.$route.query.page;
            if(_.isInteger(page)){
                this.currentPage = page;
            }else{
                this.currentPage = 1;
            }
            this.getCustomers(page);
        }
    }
</script>

<style scoped>

</style>
