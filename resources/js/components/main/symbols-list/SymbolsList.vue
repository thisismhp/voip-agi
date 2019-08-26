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
                    <tr v-for="(symbol, index) in symbols" @click="showSymDialog(symbol,index)" class="pointer-cursor">
                        <td>{{index + 1}}</td>
                        <td>{{symbol.fName}}</td>
                        <td>{{symbol.symbolId}}</td>
                        <td v-if="symbol.m_file === 0 || symbol.m_file === null" class="text-danger">ثبت نشده</td>
                        <td v-else class="text-success">ثبت شده</td>
                        <td v-if="symbol.w_file === 0 || symbol.w_file === null" class="text-danger">ثبت نشده</td>
                        <td v-else class="text-success">ثبت شده</td>
                        <td v-if="symbol.unit !== null" class="text-success">{{symbol.unit.name}}</td>
                        <td v-else class="text-danger">ثبت شده</td>
                        <td v-if="symbol.is_active" class="text-success">فعال</td>
                        <td v-else class="text-danger">غیرفعال</td>
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
                                <div class="form-group col-md-1">
                                    <label for="i-sym-ia">فعال</label>
                                    <input type="checkbox" v-model="symbolData.is_active" id="i-sym-ia" class="form-control" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="i-sym-fm">فایل العان (مرد)</label>
                                    <b-form-file v-model="symbolData.m_file" id="i-sym-fm" accept=".mp3" plain/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="i-sym-wm">فایل العان (زن)</label>
                                    <b-form-file v-model="symbolData.w_file" id="i-sym-wm" accept=".mp3" plain/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="i-sym-unit">واحد</label>
                                    <select v-model="symbolData.unit_id" id="i-sym-unit" class="form-control" >
                                        <option value="null" disabled selected>انتخاب کنید</option>
                                        <option v-for="unit in units" v-bind:value="unit.id">
                                            {{unit.name}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <br />
                            <div class="form-row">
                                <button @click="updateSym" class="btn btn-success" :disabled="sendingSym">ثبت
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
    import axios from 'axios';
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
                units:[],
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
                    unit_id:null
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
                this.symbolData.m_file = null;
                this.symbolData.w_file = null;
                this.symbolData.is_active = symbol.is_active;
                this.symbolData.unit_id = (symbol.unit !== null)?symbol.unit.id:null;
                this.symbolData.index = index;
                $('#cmt-modal').modal('show');
            },
            getSymbols(){
                axios.get('api/symbol')
                    .then(res => {
                        this.symbols = res.data;
                        axios.get('api/unit')
                            .then((res) => {
                                this.units = res.data;
                                this.loading = false;
                            })
                            .catch(() => {
                                this.err(err)
                            })
                        ;
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
            },
            updateSym(){
                this.sendingSym = true;
                const raw = this.symbolData;
                let formData = new FormData();
                formData.append('_method', 'PATCH');
                formData.append('is_active',(raw.is_active === 1 || raw.is_active === true)?1:0);
                if(raw.unit_id !== null){
                    formData.append('unit_id',raw.unit_id);
                }
                if(raw.m_file !== null){
                    formData.append('m_file',raw.m_file);
                }
                if(raw.w_file !== null){
                    formData.append('w_file',raw.w_file);
                }
                axios.post(`api/symbol/${this.symbolData.id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(() => {
                        this.getSymbols();
                        $('#cmt-modal').modal('hide');
                        this.sendingSym = false;
                    })
                    .catch((err) => {
                        this.sendingSym = false;
                        this.errorHandling(err);
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
