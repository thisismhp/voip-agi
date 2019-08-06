<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="ncc-list">
            <h3>لیست اتمام اعتبار (بررسی شده)</h3>
            <div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>توضیحات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(customer, index) in customers">
                        <td>{{index + 1}}</td>
                        <td><router-link :to="'/customer/'+ customer.id">{{customer.name}}</router-link></td>
                        <td v-if="customer.end_charge_comment === null"><span class="pointer-cursor btn btn-warning" @click="showCmtDialog(customer,index)">اضافه کنيد</span></td>
                        <td v-else-if="customer.end_charge_comment !== null" class="pointer-cursor" @click="showCmtDialog(customer,index)">{{customer.end_charge_comment}}</td>
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
                                <textarea class="form-control" v-model="cmtData.text"></textarea>
                            </div>
                            <br />
                            <div class="form-row">
                                <button @click="submitCmt(cmtData)" class="btn btn-success" :disabled="sendingCmt">ثبت
                                    <span v-if="sendingCmt" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
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
    import axios from 'axios';
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";
    import DialogMessage from "../../layout/element/DialogMessage";
    export default {
        name: "NoChargeChecked",
        components: {DialogMessage, Loading},
        data(){
            return {
                loading: true,
                loadFailed:false,
                sendingCmt:false,
                dialogVars:{
                    title:'',
                    content:'',
                    mode:'',
                    show:false,
                    showTime:2000

                },
                cmtData:{
                    id:null,
                    text:null,
                    index:null
                },
                customers:[]
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
            showDialog(show, title, content, mode, showTime){
                this.dialogVars.show=show;
                this.dialogVars.title=title;
                this.dialogVars.content=content;
                this.dialogVars.mode=mode;
                this.dialogVars.showTime=showTime;
            },
            getCustomers(){
                axios.get('api/no_charge?state=3')
                    .then(res => {
                        this.customers = res.data;
                        this.loading = false;

                    })
                    .catch(err => {
                        this.err(err)
                    })
                ;
            },
            showCmtDialog(customer,index){
                this.cmtData.text = customer.end_charge_comment;
                this.cmtData.id = customer.id;
                this.cmtData.index = index;
                $('#cmt-modal').modal('show');
            },
            submitCmt(cmtData){
                this.sendingCmt = true;
                axios.post('/api/cmt',cmtData)
                    .then((res) => {
                        this.customers[cmtData.index].end_charge_comment = res.data.end_charge_comment;
                        $('#cmt-modal').modal('hide');
                        this.cmtData = {
                            id:null,
                            text:null,
                            index:null
                        };
                        this.sendingCmt = false;
                    })
                    .catch((err) => {
                        $('#cmt-modal').modal('hide');
                        this.cmtData = {
                            id:null,
                            text:null,
                            index:null
                        };
                        this.sendingCmt = false;
                        this.errorHandling(err);
                    })
                ;
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
        },
        created() {
            this.getCustomers();
        }
    }
</script>

<style scoped>

</style>
