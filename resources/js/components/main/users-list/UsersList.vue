<template>
    <Loading v-if="loading" />
    <div class="failed center-align" v-else-if="loadFailed">
        <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
    </div>
    <div v-else-if="!loading">
        <div id="users-list">
            <h3>لیست کاربران</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام کاربری</th>
                        <th>نام</th>
                        <th>تعداد سرویس های تحت مدیریت</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user, index) in users">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="'/user/'+ user.id">{{user.username}}</router-link></td>
                        <td>{{user.name}}</td>
                        <td>{{user.services_count}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";

    export default {
        name: "UsersList",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                users:[]
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
                this.getUsers();
            },
            getUsers(){
                axios.get('api/user')
                    .then(res => {
                        this.users = res.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            }
        },
        created() {
            this.getUsers();
        }
    }
</script>

<style scoped>

</style>
