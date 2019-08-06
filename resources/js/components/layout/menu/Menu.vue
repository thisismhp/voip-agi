<template>
    <div id="menu">
        <div class="sidebar">
            <button @click="logout" style="margin:5px" class="btn btn-secondary" :disabled="sending">{{$t("words.logout")}}
                <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
            <div id="customer-mng-links">
                <router-link to="/" active-class="active" exact>{{$t("words.dashboard")}}</router-link>
                <div class="menu-title">{{$t("words.customersManage")}}</div>
                <router-link to="/add-customer" active-class="active">{{$t("words.addCustomer")}}</router-link>
                <router-link to="/customer-list" active-class="active">{{$t("words.customersList")}}</router-link>
                <router-link to="/demo-users-list" active-class="active">{{$t("words.demoUsersList")}}</router-link>
                <router-link to="/charge-one-customer" active-class="active">{{$t("words.ioc")}}</router-link>
                <router-link to="/charge-one-demo-user" active-class="active">{{$t("words.iod")}}</router-link>
                <router-link to="/charge-multi-customer" active-class="active">{{$t("words.imc")}}</router-link>
                <router-link to="/charge-multi-demo-user" active-class="active">{{$t("words.imd")}}</router-link>
                <router-link to="/no-charge-checked-list" active-class="active">{{$t("words.cNoChargeList")}}</router-link>
                <router-link to="/no-charge-unchecked-list" active-class="active">{{$t("words.ucNoChargeList")}}</router-link>
            </div>
            <div id="customer-serv-links">
                <div class="menu-title">{{$t("words.servicesManage")}}</div>
                <router-link to="/add-service" active-class="active" exact>{{$t("words.addService")}}</router-link>
                <router-link to="/services-list" active-class="active">{{$t("words.servicesList")}}</router-link>
                <router-link to="/add-service-groups" active-class="active">{{$t("words.addServiceGroup")}}</router-link>
                <router-link to="/symbols-list" active-class="active">{{$t("words.symbolsList")}}</router-link>
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
