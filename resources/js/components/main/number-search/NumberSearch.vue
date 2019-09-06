<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div class="tiny-margin-t" v-else-if="!loading">
            <input type="text" placeholder="جست و جو با شماره" class="form-control" @keypress="isNumber($event)" @input="filter($event.target.value)"/>
            <div class="form-row tiny-margin-t">
                <div class="col-md-6 border-left">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>نام</th>
                            <th>افزایش اعتبار</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(customer) in filterCustomers">
                            <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                            <td><router-link class="btn btn-secondary" :to="`/charge-one-customer?id=${customer.id}`">افزایش اعتبار</router-link></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 border-right">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>شماره</th>
                            <th>افزایش اعتبار</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(demo) in filterDemoUsers">
                            <td>{{demo.phone_number}}</td>
                            <td><router-link class="btn btn-secondary" :to="`/charge-one-customer?id=${demo.id}`" >افزایش اعتبار</router-link></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";

    export default {
        name: "NumberSearch",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                customers:[],
                filterCustomers:[],
                demoUsers:[],
                filterDemoUsers:[],
            }
        },
        methods:{
            isNumber : mixins.isNumber,
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
                this.init();
            },
            init(){
                axios.get('api/demo_user')
                    .then((res) => {
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
                        this.err(err);
                    })
                ;
            },
            filter(input){
                this.filterCustomer(input);
                this.filterDemo(input);
            },
            filterCustomer(input){
                const customers = this.customers;
                let exp = [];
                if(input === null || input === ''){
                    exp = [];
                }else {
                    for(let item in customers){
                        if (!customers.hasOwnProperty(item)) continue;
                        const numbers = customers[item].numbers;
                        for(let ni in numbers){
                            if (!numbers.hasOwnProperty(ni)) continue;
                            if(numbers[ni].phone_number.search(input) > -1){
                                exp.push(customers[item]);
                            }
                        }
                    }
                }
                this.filterCustomers = exp;
            },
            filterDemo(input){
                const demo = this.demoUsers;
                let exp = [];
                if(input === null || input === ''){
                    exp = [];
                }else {
                    for(let item in demo){
                        if (!demo.hasOwnProperty(item)) continue;
                        if(demo[item].phone_number.search(input) > -1){
                            exp.push(demo[item]);
                        }
                    }
                }
                this.filterDemoUsers = exp;
            }
        },
        created() {
            this.init();
        }
    }
</script>

<style scoped>

</style>
