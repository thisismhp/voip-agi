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
                        <th>شهر</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(customer, index) in customers">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                        <td>{{customer.city.name}}</td>
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
