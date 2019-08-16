<template>
    <div>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="symbol-list">
            <h3>لیست نماد ها</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>کد</th>
                        <th>فایل(مرد)</th>
                        <th>فایل(زن)</th>
                        <th>واحد</th>
                        <th>قیمت فروش</th>
                        <th>قیمت خرید</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(symbol, index) in symbols">
                        <td>{{index + 1}}</td>
                        <td>{{symbol.fName}}</td>
                        <td>{{symbol.symbolId}}</td>
                        <td>{{(symbol.m_file !== null)?symbol.m_file:'ندارد'}}</td>
                        <td>{{(symbol.w_file !== null)?symbol.w_file:'ندارد'}}</td>
                        <td>{{(symbol.unit !== null)?symbol.unit.name:'ندارد'}}</td>
                        <td>{{symbol.sellPriceFormatted}}</td>
                        <td>{{symbol.buyPriceFormatted}}</td>
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
        name: "SymbolsList",
        components: {Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                symbols:[]
            }
        },
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
        getCustomers(){
            axios.get('api/customer')
                .then(res => {
                    this.customers = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.err(err)
                })
            ;
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
            getSymbols(){
                axios.get('api/symbol')
                    .then(res => {
                        this.symbols = res.data;
                        this.loading = false;

                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            }
        },
        created() {
            this.getSymbols();
        }
    }
</script>

<style scoped>

</style>
