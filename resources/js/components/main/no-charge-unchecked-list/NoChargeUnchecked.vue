<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="ncu-list">
            <h3>لیست اتمام اعتبار (بررسی نشده)</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>بررسی</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(customer, index) in customers">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                        <td>
                            <button @click="check(index,customer.id)" class="btn btn-success" :disabled="sending">بررسی شد
                                <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Loading from "../../layout/element/Loading";
    export default {
        name: "NoChargeUnChecked",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                sending:false,
                customers:[]
            }
        },
        methods:{
            err(err){
                this.loadFailed = true;
                this.loading = false;
                return err;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.getCustomers();
            },
            getCustomers(){
                axios.get('api/no_charge?state=2')
                    .then(res => {
                        this.customers = res.data;
                        this.loading = false;

                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            },
            check(index, id){
                this.sending = true;
                axios.post('/api/check_charge',{customer_id:id})
                    .then(() => {
                        this.loading = true;
                        this.loadFailed =false;
                        this.sending = false;
                        this.getCustomers();
                    })
                    .catch(() => {

                    })
            }
        },
        created() {
            this.getCustomers();
        }
    }
</script>

<style scoped>

</style>
