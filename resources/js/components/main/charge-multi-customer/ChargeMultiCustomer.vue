<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="cmc-form">
            <h3 class="border-bottom">افزایش اعتبار گروهی مشتریان</h3>
            <div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="phone-charge-type">نوع اعتبار</label>
                        <select v-model="chargeData.charge_type_id" id="phone-charge-type" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="chargeType in chargeTypes" v-bind:value="chargeType.id">
                                {{chargeType.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="value">مقدار</label>
                        <input @keyup.enter="save" type="text" v-model="chargeData.value" id="value" class="form-control" @keypress="isNumber($event)" />
                    </div>
                </div>
                <div class="form-row">
                    <button @click="save" class="btn btn-success" :disabled="sending">ثبت
                        <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
                <hr />
                <div class="form-row">
                    <h4>مشتریان</h4>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نام</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(cd, index) in cds">
                            <td>
                                <input :id="`cd${index}`" type="checkbox" :value="cd.id" v-model="chargeData.items"/>
                                <label :for="`cd${index}`">{{index + 1}}</label>
                            </td>
                            <td>{{cd.name}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DialogMessage from "../../layout/element/DialogMessage";
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";
    import axios from "axios";
    export default {
        name: "ChargeMultiCustomer",
        components: {Loading, DialogMessage},
        data(){
            return {
                sending: false,
                loading: true,
                loadFailed:false,
                dialogVars:{
                    title:'',
                    content:'',
                    mode:'',
                    show:false,
                    showTime:2000
                },
                chargeTypes : [],
                cds : [],
                cdx : [],
                chargeData : {
                    destination_type:1,
                    charge_type_id : null,
                    value : null,
                    items:this.cdx
                },
            }
        },
        methods:{
            isNumber : mixins.isNumber,
            err(err){
                this.loadFailed = true;
                this.loading = false;
                if(err.response) {
                    if (err.response.status === 401) {
                        this.$store.state.authCheck = false;
                    }
                }
                return err;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.initForm();
            },
            showDialog(show, title, content, mode, showTime){
                this.dialogVars.show=show;
                this.dialogVars.title=title;
                this.dialogVars.content=content;
                this.dialogVars.mode=mode;
                this.dialogVars.showTime=showTime;
            },
            initForm() {
                axios.get('/api/charge_type')
                    .then(res => {
                        this.chargeTypes = res.data;
                        axios.get('/api/customer')
                            .then(res => {
                                this.cds = res.data;
                                this.cdx = mixins.extractId(this.cds);
                                this.chargeData.items = this.cdx;
                                this.loading = false;
                            })
                            .catch(err => {
                                this.err(err);
                            })
                        ;
                    })
                    .catch(err => {
                        this.err(err);
                    })
                ;
            },
            errorHandling(err){
                if(err.response){
                    if(err.response.status === 422){
                        this.showDialog(true, "ثبت ناموفق",mixins.parseValidation(err.response),'danger',0);
                    }else if(err.response.status === 401){
                        this.$store.state.authCheck = false;
                    }else{
                        this.showDialog(true, "خطای سرور",'مشکلی در سرور به وجود آمده است','danger',0);
                    }
                }else {
                    this.showDialog(true, "خطای ارتباط با سرور","ارتباط با سرور انجام نشد",'danger',0);
                }
            },
            save(){
                this.sending = true;
                axios.post('/api/charge',this.chargeData)
                    .then(() => {
                        this.sending = false;
                        this.chargeData = {
                            destination_type:1,
                            charge_type_id : null,
                            value : null,
                            items:this.cdx
                        };
                        this.showDialog(true, "ثبت موفق","اطلاعات با موفقیت ثبت شد.",'success',2000);
                    })
                    .catch((err) => {
                        this.sending = false;
                        this.errorHandling(err);
                    })
                ;
            },
        },
        created() {
            this.initForm();
        }
    }
</script>

<style scoped>

</style>
