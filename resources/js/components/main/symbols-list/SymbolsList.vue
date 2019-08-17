<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="symbol-list">
            <h3>لیست نماد ها</h3>
            <div class="form-row tiny-margin-b">
                <button @click="update" class="btn btn-success" :disabled="sending">به روزرسانی نماد ها
                    <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
            </div>
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
                        <th>وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(symbol, index) in symbols">
                        <td>{{index + 1}}</td>
                        <td class="pointer-cursor" @click="showSymDialog(symbol,index)">{{symbol.fName}}</td>
                        <td>{{symbol.symbolId}}</td>
                        <td>{{(symbol.m_file !== null)?symbol.m_file:'ندارد'}}</td>
                        <td>{{(symbol.w_file !== null)?symbol.w_file:'ندارد'}}</td>
                        <td>{{(symbol.unit !== null)?symbol.unit.name:'ندارد'}}</td>
                        <td>{{(symbol.is_active)?'فعال':'غیرفعال'}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div class="modal fade" id="cmt-modal" tabindex="-1" role="dialog" aria-labelledby="myMessage" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span style="font-size: 4vh;" aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="exampleModalLabel">ویرایش</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-row">
                                <div>

                                </div>
                            </div>
                            <br />
                            <div class="form-row">
                                <button @click="" class="btn btn-success" :disabled="sendingSym">ثبت
                                    <span v-if="sendingSym" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "../../layout/element/Loading";
    import DialogMessage from "../../layout/element/DialogMessage";
    import {mixins} from "../../../mixins";
    export default {
        name: "SymbolsList",
        components: {DialogMessage, Loading},
        data(){
            return {
                loading: true,
                sending: false,
                loadFailed:false,
                sendingSym:false,
                symbols:[],
                dialogVars:{
                    title:'',
                    content:'',
                    mode:'',
                    show:false,
                    showTime:2000
                },
                symbolData:{
                    id:null,
                    index:null,
                    m_file:null,
                    w_file:null,
                    is_active:null,
                },
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
            errorHandling(err){
                if(err.response){
                    if(err.response.status === 422){
                        this.showDialog(true, "ثبت ناموفق",mixins.parseValidation(err.response),'danger',0);
                    }else if(err.response.status === 401){
                        this.$store.state.authCheck = false;
                    }
                    else{
                        this.showDialog(true, "خطای سرور",'مشکلی در سرور به وجود آمده است','danger',0);
                    }
                }else {
                    this.showDialog(true, "خطای ارتباط با سرور","ارتباط با سرور انجام نشد",'danger',0);
                }
            },
            showDialog(show, title, content, mode, showTime){
                this.dialogVars.show=show;
                this.dialogVars.title=title;
                this.dialogVars.content=content;
                this.dialogVars.mode=mode;
                this.dialogVars.showTime=showTime;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.getSymbols();
            },
            showSymDialog(symbol,index){
                this.symbolData.id = symbol.id;
                this.symbolData.m_file = symbol.m_file;
                this.symbolData.w_file = symbol.w_file;
                this.symbolData.is_active = symbol.is_active;
                this.symbolData.index = index;
                $('#cmt-modal').modal('show');
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
            },
            update() {
                this.sending = true;
                axios.post('api/symbol')
                    .then(res => {
                        this.symbols = res.data;
                        this.sending = false;

                    })
                    .catch(() => {
                        this.sending = false;
                        this.showDialog(true, "خطای ارتباط با سرور","ارتباط با سرور انجام نشد",'danger',0);
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
