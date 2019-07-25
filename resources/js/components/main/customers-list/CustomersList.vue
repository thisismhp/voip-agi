<template>
    <div id="customer-list">
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
</template>

<script>
    import axios from 'axios'
    export default {
        name: "CustomersList",
        data(){
            return {
                customers:[]
            }
        },
        methods:{
            getCustomers(){
                axios.get('api/customer')
                    .then(res => {
                        this.customers = res.data;
                    })
                    .catch(err => {
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
