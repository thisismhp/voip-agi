<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="customer-list">
            <h3>لیست مشتریان</h3>
            <div>
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
                    <tr v-for="(customer, index) in customers">
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
                customers:[]
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
            getCustomers(){
                axios.get('api/customer')
                    .then(res => {
                        this.customers = res.data;
                        this.loading = false;

                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            }
        },
        created() {
            this.getCustomers();
        }
    }
</script>

<style scoped>

</style>
