<template>
    <div id="content" class="content container-fluid">
        <Header />
        <Main />
    </div>
</template>

<script>
    import Main from "../../main/Main";
    import Header from "../header/Header";
    export default {
        name: "Content",
        components: {Header, Main},
    }
</script>

<style scoped>

</style>
