<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="service-form">
            <h3 class="border-bottom">سرویس جدید</h3>
            <div>
                <h5>مشخصات سرویس</h5>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="i-service-name">عنوان سرویس</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.name" id="i-service-name" class="form-control" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-service-m_line">شماره سر خط (مرد)</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.m_line" id="i-service-m_line" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-service-w_line">شماره سر خط (زن)</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.w_line" id="i-service-w_line" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="i-service-ws_address">آدرس وب سرویس</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.ws_address" id="i-service-ws_address" class="form-control" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="i-service-ws_username">نام کاربری وب سرویس</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.ws_username" id="i-service-ws_username" class="form-control" />
                    </div>
                    <div class="form-group col-md-5">
                        <label for="i-service-ws_password">رمز عبور وب سرویس</label>
                        <input @keyup.enter="save" type="password" v-model="serviceData.ws_password" id="i-service-ws_password" class="form-control" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="i-service-ws_password">تست</label>
                        <button @click="test" class="btn btn-success form-control" :disabled="sending">تست وب سرویس
                            <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="i-service-ws_update_interval">فاصله زمانی به روزرسانی وب سرویس (ثانیه)</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.ws_update_interval" id="i-service-ws_update_interval" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="i-service-user_id">کاربر اپراتور</label>
                        <select v-model="serviceData.user_id" id="i-service-user_id" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="user in users" v-bind:value="user.id">
                                {{user.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="i-service-queue_id">شناسه صف تماس</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.queue_id" id="i-service-queue_id" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="i-service-demo_first_charge">اعتبار اولیه دمو</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.demo_first_charge" id="i-service-demo_first_charge" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="i-service-demo_charge_type_id">نوع اعتبار دمو</label>
                        <select v-model="serviceData.demo_charge_type_id" id="i-service-demo_charge_type_id" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="chargeType in chargeTypes" v-bind:value="chargeType.id">
                                {{chargeType.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="i-service-demo_use_charge_type_id">نوع اعتبارسنجی دمو</label>
                        <select v-model="serviceData.demo_use_charge_type_id" id="i-service-demo_use_charge_type_id" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="chargeType in useChargeTypes" v-bind:value="chargeType.id">
                                {{chargeType.name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-1">
                        <label for="i-service-is_active">فعال</label>
                        <input type="checkbox" v-model="serviceData.is_active" id="i-service-is_active" class="form-control"/>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="i-service-customer_is_free">رایگان برای مشتری</label>
                        <input type="checkbox" v-model="serviceData.customer_is_free" id="i-service-customer_is_free" class="form-control"/>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="i-service-demo_is_free">رایگان برای دمو</label>
                        <input type="checkbox" v-model="serviceData.demo_is_free" id="i-service-demo_is_free" class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <button @click="save" class="btn btn-success" :disabled="sending">ثبت
                    <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import DialogMessage from "../../layout/element/DialogMessage";
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";
    export default {
        name: "AddService",
        components: {Loading, DialogMessage},
        data(){
            return {
                sending: false,
                loading: true,
                loadFailed: false,
                dialogVars: {
                    title: '',
                    content: '',
                    mode: '',
                    show: false,
                    showTime: 2000
                },
                serviceData:{
                    name:null,
                    m_line:null,
                    w_line:null,
                    is_active:true,
                    queue_id:null,
                    customer_is_free:false,
                    demo_is_free:false,
                    demo_first_charge:null,
                    demo_charge_type_id:null,
                    demo_use_charge_type_id:null,
                    ws_address:null,
                    ws_username:null,
                    ws_password:null,
                    ws_update_interval:null,
                    user_id:null,
                },
                chargeTypes: [],
                useChargeTypes: [],
                users:[]
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
                this.initForm();
            },
            initForm(){
                axios.get('/api/charge_type?is_charge=1')
                    .then(res => {
                        this.chargeTypes = res.data;
                        axios.get('/api/user')
                            .then(res => {
                                this.users = res.data;
                                axios.get('/api/charge_type')
                                    .then(res => {
                                        this.useChargeTypes = res.data;
                                        this.loading = false;
                                    })
                                    .catch((err) => {
                                        this.err(err);
                                    })
                                ;
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
            test(){
                this.sending = true;
                axios.post('api/check_service',this.serviceData)
                    .then(res => {
                        if(res.data === 'OK'){
                            this.showDialog(true, "تست موفق","تست موفقیت آمیز بود!",'success',2000);
                        }else{
                            this.showDialog(true, "خطا","وب سرویس در دسترس نیست",'danger',2000);
                        }
                        this.sending = false;
                    })
                    .catch(()=> {
                        this.sending = false;
                        this.showDialog(true, "خطا","وب سرویس در دسترس نیست",'danger',2000);
                    })
                ;
            },
            save(){
                this.sending = true;
                axios.post('/api/service',this.serviceData)
                    .then(() => {
                        this.sending = false;
                        this.serviceData = {
                            name:null,
                            m_line:null,
                            w_line:null,
                            is_active:true,
                            queue_id:null,
                            customer_is_free:false,
                            demo_is_free:false,
                            demo_first_charge:null,
                            demo_use_charge_type_id:null,
                            ws_address:null,
                            ws_username:null,
                            ws_password:null,
                            ws_update_interval:null,
                            user_id:null,
                        };
                        this.$store.state.crs = true;
                        this.showDialog(true, "ثبت موفق","اطلاعات با موفقیت ثبت شد.",'success',2000);
                    })
                    .catch(err => {
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
