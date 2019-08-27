<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="form-row">
            <div class="form-group col-md-12">
                <span>نام : </span>
                <span>{{user.name}}</span>
            </div>
            <div class="form-group col-md-12">
                <span>نام کاربری : </span>
                <span>{{user.name}}</span>
            </div>
            <hr />
            <h4>تغییر رمز عبور</h4>
            <div class="form-group col-md-12">
                <label for="i-pass">رمز عبور جدید</label>
                <input @keyup.enter="savePass" type="password" v-model="newPass.password" id="i-pass" class="form-control" />
            </div>
            <div class="form-group col-md-12">
                <label for="i-pass-c">تکرار رمز عبور جدید</label>
                <input @keyup.enter="savePass" type="password" v-model="newPass.password_confirmation" id="i-pass-c" class="form-control" />
            </div>
            <div class="form-row">
                <button @click="savePass" class="btn btn-success" :disabled="sending">ثبت
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
        name: "My",
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
                user:[],
                newPass:{
                    password:null,
                    password_confirmation:null
                }
            }
        },
        methods: {
            err(err) {
                this.loadFailed = true;
                this.loading = false;
                if (err.response.status === 401) {
                    this.$store.state.authCheck = false;
                }
                return err;
            },
            reload() {
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
            getUser() {
                axios.get('/api/access')
                    .then(res => {
                        this.user = res.data;
                        this.loading = false;

                    })
                    .catch(err => {
                        this.err(err)
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
            savePass(){
                this.sending = true;
                axios.post('/api/my',this.newPass)
                    .then(() => {
                        this.newPass = {
                            password:null,
                            password_confirmation:null
                        };
                        this.showDialog(true, "ثبت موفق","رمز عبور شما با موفقیت عوض شد",'success',2000);
                        this.sending = false;
                    })
                    .catch((err) => {
                        this.sending = false;
                        this.errorHandling(err);
                    })
                ;
            }
        },
        created() {
            this.getUser();
        }
    }
</script>

<style scoped>

</style>
