<template>
    <div id="menu">
        <nav id="sidebar" class="sidebar active">
            <div class="tiny-margin d-block d-sm-none d-none d-sm-block d-md-none">
                <button type="button" @click="hideSide" class="btn btn-secondary">
                    <img class="menu-icon" src="../../../../image/menu.png" alt="Menu" />
                </button>
            </div>
            <div id="customer-mng-links">
                <router-link @click.native="menuSlideHide" to="/" active-class="active" exact>{{$t("words.dashboard")}}</router-link>
                <div class="menu-title">{{$t("words.customersManage")}}</div>
                <router-link @click.native="menuSlideHide" to="/add-customer" active-class="active">{{$t("words.addCustomer")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/customer-list" active-class="active">{{$t("words.customersList")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/demo-users-list" active-class="active">{{$t("words.demoUsersList")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/charge-one-customer" active-class="active">{{$t("words.ioc")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/charge-one-demo-user" active-class="active">{{$t("words.iod")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/charge-multi-customer" active-class="active">{{$t("words.imc")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/charge-multi-demo-user" active-class="active">{{$t("words.imd")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/no-charge-checked-list" active-class="active">{{$t("words.cNoChargeList")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/no-charge-unchecked-list" active-class="active">{{$t("words.ucNoChargeList")}}</router-link>
            </div>
            <div id="customer-serv-links">
                <div class="menu-title">{{$t("words.servicesManage")}}</div>
                <router-link @click.native="menuSlideHide" to="/add-service" active-class="active" exact>{{$t("words.addService")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/services-list" active-class="active">{{$t("words.servicesList")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/add-service-groups" active-class="active">{{$t("words.addServiceGroup")}}</router-link>
                <router-link @click.native="menuSlideHide" to="/symbols-list" active-class="active">{{$t("words.symbolsList")}}</router-link>
            </div>
        </nav>
    </div>
</template>

<script>
    import {mixins} from "../../../mixins";

    export default {
        name: "Menu",
        methods:{
            hideSide : mixins.hideSide,
            menuSlideHide () {
                console.log(window.innerWidth);
                if(window.innerWidth < 768){
                    this.hideSide();
                }
            }
        }
    }
</script>

<style scoped>

</style>
