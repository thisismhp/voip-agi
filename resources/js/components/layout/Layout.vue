<template>
    <div id="layout">
        <Menu />
        <Content />
    </div>
</template>

<script>
    import Menu from "./menu/Menu";
    import Content from "./content/Content";
    export default {
        name: "Layout",
        components: {Content, Menu}
    }
</script>

<style scoped>

</style>