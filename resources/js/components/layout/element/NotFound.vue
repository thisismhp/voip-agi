<template>
    <div class="not-found">
        <h1 class="center-align">
            404
        </h1>
        <h1 class="center-align">
            {{notFoundTitle}}
            مورد نظر شما وجود ندارد.
        </h1>
    </div>
</template>

<script>
    export default {
        name: "NotFound",
        props:{
            notFoundTitle:{
                required:false,
                default:'صفحه',
            }
        },
    }
</script>

<style scoped>

</style>
