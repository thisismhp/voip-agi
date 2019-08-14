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
                    <div class="form-group col-md-6">
                        <label for="i-service-ws_username">نام کاربری وب سرویس</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.ws_username" id="i-service-ws_username" class="form-control" />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-service-ws_password">رمز عبور وب سرویس</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.ws_password" id="i-service-ws_password" class="form-control" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-service-ws_update_interval">فاصله زمانی به روزرسانی وب سرویس (ثانیه)</label>
                        <input @keyup.enter="save" type="text" v-model="serviceData.ws_update_interval" id="i-service-ws_update_interval" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-service-user_id">کاربر اپراتور</label>
                        <select v-model="serviceData.user_id" id="i-service-user_id" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="user in users" v-bind:value="user.id">
                                {{user.name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-1">
                        <label for="i-service-is_active">فعال</label>
                        <input @keyup.enter="save" type="checkbox" v-model="serviceData.is_active" id="i-service-is_active" class="form-control" @keypress="isNumber($event)"/>
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
                    ws_address:null,
                    ws_username:null,
                    ws_password:null,
                    ws_update_interval:null,
                    user_id:null,
                },
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
                axios.get('/api/user')
                    .then(res => {
                        this.users = res.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.err(err);
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
