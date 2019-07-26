<template>
    <div>
        <h1 class="center-align not-found">
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