<template>
    <div id="demo-users-list">
        <h3>لیست کاربران دمو</h3>
        <div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ردیف</th>
                    <th>شماره</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(demoUser, index) in demoUsers">
                    <td>{{index + 1}}</td>
                    <td>{{demoUser.phone_number}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "DemoUsersList",
        data(){
            return {
                demoUsers:[]
            }
        },
        methods:{
            getDemoUsers(){
                axios.get('api/demo_user')
                    .then(res => {
                        this.demoUsers = res.data;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            }
        },
        created() {
            this.getDemoUsers();
        }
    }
</script>

<style scoped>

</style>
