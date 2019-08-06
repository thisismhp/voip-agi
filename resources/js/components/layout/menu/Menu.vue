<template>
    <div id="menu">
        <div class="sidebar">
            <button @click="logout" style="margin:5px" class="btn btn-secondary" :disabled="sending">خروج
                <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
            <div id="customer-mng-links">
                <router-link to="/" active-class="active" exact>صفحه اصلی</router-link>
                <div class="menu-title">مدیریت مشتریان</div>
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
            <div id="customer-serv-links">
                <div class="menu-title">مدیریت سرویس ها</div>
                <router-link to="/add-service" active-class="active" exact>افزودن سرویس جدید</router-link>
                <router-link to="/services-list" active-class="active">لیست سرویس ها</router-link>
                <router-link to="/add-service-groups" active-class="active">افزودن گروه های خدمات</router-link>
                <router-link to="/symbols-list" active-class="active">لیست نمادها</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "Menu",
        data(){
            return{
                sending: false,
            }
        },
        methods:{
            logout(){
                this.sending = true;
                axios.patch('/api/logout')
                    .then(() => {
                        this.$store.state.authCheck = false;
                        window.axios.defaults.headers.common['Authorization'] = null;
                        this.sending = false;
                    })
                    .catch(() => {
                        this.sending = false;
                    })
                ;
            }
        }
    }
</script>

<style scoped>

</style>
