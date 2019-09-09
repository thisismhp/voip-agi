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
                    <div class="form-group col-md-6">
                        <label for="time_value">مقدار تعدادی</label>
                        <input @keyup.enter="save" type="text" v-model="chargeData.time_value" id="time_value" class="form-control" @keypress="isNumber($event)" />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date_value">مقدار بازه ای</label>
                        <input @keyup.enter="save" type="text" v-model="chargeData.date_value" id="date_value" class="form-control" @keypress="isNumber($event)" />
                    </div>
                </div>
                <div class="form-row">
                    <button @click="save" class="btn btn-success" :disabled="sending">ثبت
                        <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
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
                chargeData : {
                    destination_type:3,
                    time_value : 0,
                    date_value : 0,
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
                this.loading = false;
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
                            destination_type:3,
                            time_value : 0,
                            date_value : 0,
                        };
                        this.showDialog(true, "ثبت موفق","اطلاعات با موفقیت ثبت شد.",'success',2000);
                        // this.$router.replace('/customer-list');
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
