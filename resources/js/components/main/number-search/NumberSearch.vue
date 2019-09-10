<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div class="tiny-margin-t" v-else-if="!loading">
            <div class="form-row">
                <div class="col-md-10">
                    <input type="text" v-model="key" placeholder="جست و جو با شماره" class="form-control" @keypress="isNumber($event)" @keyup.enter="save"/>
                </div>
                <div class="col-md-2">
                    <button @click="save" class="btn btn-success" :disabled="sending">جست و جو
                        <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <div class="form-row tiny-margin-t">
                <div class="col-md-6">
                    <p>جست و جو در مشتریان</p>
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
                <div class="col-md-6">
                    <p>جست و جو در کاربران دمو</p>
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
                sending:false,
                key:null,
                filterCustomers:[],
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
                this.loading = false;
            },
            save(){
                this.filterDemoUsers = [];
                this.filterCustomers = [];
                if(this.key === null || this.key === ''){
                    return;
                }
                this.sending = true;
                axios.post('/api/search',{key:this.key})
                    .then(res => {
                        this.sending = false;
                        this.filterDemoUsers = res.data.demo;
                        this.filterCustomers = res.data.customer;
                    })
                    .catch(err => {
                        this.sending = false;
                        this.errorHandling(err);
                    })
                ;
            }
        },
        created() {
            this.init();
        }
    }
</script>

<style scoped>

</style>
