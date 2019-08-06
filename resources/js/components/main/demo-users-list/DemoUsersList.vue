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
                        <td>{{demoUser.time_charge}}</td>
                        <td>{{toJalaali(demoUser.date_charge,' ','-')}}</td>
                        <td><router-link class="btn btn-secondary" :to="`/charge-one-demo-user?id=${demoUser.id}`" >افزایش اعتبار</router-link></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
                demoUsers:[]
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
            getDemoUsers(){
                axios.get('api/demo_user')
                    .then(res => {
                        this.demoUsers = res.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            }
        },
        created() {
            this.getDemoUsers();
        }
    }
</script>

<style scoped>

</style>
