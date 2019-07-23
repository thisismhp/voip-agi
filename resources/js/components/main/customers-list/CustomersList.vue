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
                    <td>{{customer.name}}</td>
                    <td>{{customer.city}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CustomersList",
        data(){
            return {
                customers:[]
            }
        },
    }
</script>

<style scoped>

</style>
