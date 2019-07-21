<template>
    <div id="menu">
        <div class="sidebar">
            <router-link to="/add-customer" active-class="active">افزودن مشتری جدید</router-link>
            <router-link to="/customer-list" active-class="active">لیست مشتریان</router-link>
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