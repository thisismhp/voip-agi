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
                <tr v-for="(demo_user, index) in demo_users">
                    <td>{{index + 1}}</td>
                    <td>{{demo_user.number}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DemoUsersList",
        data(){
            return {
                demo_users:[]
            }
        }
    }
</script>

<style scoped>

</style>
