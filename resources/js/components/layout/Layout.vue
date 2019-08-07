<template>
    <div class="wrapper" id="layout">
        <Menu />
        <Content />
        <div @click="hideSide" class="overlay active"></div>
    </div>
</template>

<script>
    import Menu from "./menu/Menu";
    import Content from "./content/Content";
    import {mixins} from "../../mixins";
    export default {
        name: "Layout",
        components: {Content, Menu},
        methods:{
            hideSide:mixins.hideSide,
        }
    }
</script>

<style scoped>

</style>
