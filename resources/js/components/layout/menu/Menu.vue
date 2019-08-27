<template>
    <div id="menu">
        <nav id="sidebar" class="sidebar active">
            <button v-if="windowWidth < 769" type="button" @click="hideSide" class="btn btn-secondary menu-slide-btn">
                <img class="menu-icon" src="../../../../image/menu.png" alt="Menu" />
            </button>
            <div id="customer-mng-links">
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/" active-class="active" exact>{{$t("words.dashboard")}}</router-link>
                <div v-if="hasService" class="menu-title">{{$t("words.customersManage")}}</div>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/add-customer" active-class="active">{{$t("words.addCustomer")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/customer-list" active-class="active">{{$t("words.customersList")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/demo-users-list" active-class="active">{{$t("words.demoUsersList")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/charge-one-customer" active-class="active">{{$t("words.ioc")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/charge-one-demo-user" active-class="active">{{$t("words.iod")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/charge-multi-customer" active-class="active">{{$t("words.imc")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/charge-multi-demo-user" active-class="active">{{$t("words.imd")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/no-charge-checked-list" active-class="active">{{$t("words.cNoChargeList")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/no-charge-unchecked-list" active-class="active">{{$t("words.ucNoChargeList")}}</router-link>
            </div>
            <div id="customer-serv-links">
                <div v-if="hasService || isAdmin" class="menu-title">{{$t("words.servicesManage")}}</div>
                <router-link v-if="isAdmin" @click.native="menuSlideHide" to="/add-service" active-class="active" exact>{{$t("words.addService")}}</router-link>
                <router-link v-if="isAdmin" @click.native="menuSlideHide" to="/services-list" active-class="active">{{$t("words.servicesList")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/add-service-groups" active-class="active">{{$t("words.addServiceGroup")}}</router-link>
                <router-link v-if="hasService" @click.native="menuSlideHide" to="/symbols-list" active-class="active">{{$t("words.symbolsList")}}</router-link>
            </div>
            <div id="customer-users-links">
                <div v-if="isAdmin" class="menu-title">مدیریت کاربران</div>
                <router-link v-if="isAdmin" @click.native="menuSlideHide" to="/add-user" active-class="active" exact>افزودن کاربر جدید</router-link>
                <router-link v-if="isAdmin" @click.native="menuSlideHide" to="/users-list" active-class="active">لیست کاربران</router-link>
            </div>
        </nav>
    </div>
</template>

<script>
    import {mixins} from "../../../mixins";

    export default {
        name: "Menu",
        data(){
            return{
                windowWidth: window.innerWidth,
            }
        },
        methods:{
            hideSide : mixins.hideSide,
            menuSlideHide () {
                if(window.innerWidth < 769){
                    this.hideSide();
                }
            },
        },
        mounted() {
            this.$nextTick(() => {
                window.addEventListener('resize', () => {
                    this.windowWidth = window.innerWidth;
                });
                if(this.windowWidth < 769){
                    this.hideSide();
                }
            })
        },
        computed: {
            isAdmin () {
                return this.$store.state.isAdmin;
            },
            hasService () {
                return this.$store.state.hasService;
            }
        },
    }
</script>

<style scoped>

</style>
