<template>
    <div id="menu">
        <div class="sidebar">
            <router-link to="/" active-class="active" exact>صفحه اصلی</router-link>
            <router-link to="/add-customer" active-class="active">افزودن مشتری جدید</router-link>
            <router-link to="/customer-list" active-class="active">لیست مشتریان</router-link>
            <router-link to="/demo-users-list" active-class="active">لیست کاربران دمو</router-link>
            <router-link to="/charge-one-customer" active-class="active">افزایش اعتبار انفرادی مشتریان</router-link>
            <router-link to="/charge-one-demo-user" active-class="active">افزایش اعتبار انفرادی دمو</router-link>
            <router-link to="/charge-multi-customer" active-class="active">افزایش اعتبار گروهی مشتریان</router-link>
            <router-link to="/charge-multi-demo-user" active-class="active">افزایش اعتبار گروهی دمو</router-link>
            <router-link to="/no-charge-checked-list" active-class="active">لیست اتمام اعتبار ( بررسی شده)</router-link>
            <router-link to="/no-charge-unchecked-list" active-class="active">لیست اتمام اعتبار ( بررسی نشده)</router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Menu"
    }
</script>

<style scoped>

</style>