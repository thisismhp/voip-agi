<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="units-list">
            <h3>لیست واحد ها</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(unit, index) in units">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="'/unit/'+ unit.id">{{unit.name}}</router-link></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "../../layout/element/Loading";
    export default {
        name: "UnitList",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                units:[],
            }
        },
        methods:{
            err(err){
                this.loadFailed = true;
                this.loading = false;
                if(err.response.status === 401){
                    this.$store.state.authCheck = false;
                }
                return err;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.getCustomers();
            },
            getUnits(){
                axios.get('api/unit')
                    .then(res => {
                        this.units = res.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            },
        },
        created() {
            this.getUnits();
        }
    }
</script>

<style scoped>

</style>
