<template>
    <div id="app">
        <Layout v-if="authCheck"/>
        <Auth v-else/>
    </div>
</template>

<script>
    import Layout from "./layout/Layout";
    import Auth from "./layout/auth/Auth";
    export default {
        name: "App",
        components: {Auth, Layout},
        computed:{
            authCheck(){return this.$store.state.authCheck}
        }
    }
</script>

<style scoped>

</style>
