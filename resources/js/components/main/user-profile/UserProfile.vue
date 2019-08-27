<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <NotFound not-found-title="کاربر" v-else-if="notFound" />
        <div v-else-if="!loading" id="user-form">
            <h3 class="border-bottom">کاربر جدید</h3>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="i-name">نام (فارسی)</label>
                    <input @keyup.enter="update(id)" type="text" v-model="userData.name" id="i-name" class="form-control" :disabled="isAdmin"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="i-username">نام کاربری (انگلیسی)</label>
                    <input @keyup.enter="update(id)" type="text" v-model="userData.username" id="i-username" class="form-control inputUsername" :disabled="isAdmin"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="i-password">رمز عبور</label>
                    <input @keyup.enter="update(id)" type="text" v-model="userData.password" id="i-password" class="form-control inputPassword" :disabled="isAdmin"/>
                </div>
            </div>
            <div class="form-row">
                <button @click="update(id)" class="btn btn-success" :disabled="sending">ثبت
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
    import NotFound from "../../layout/element/NotFound";

    export default {
        name: "UserProfile",
        components: {NotFound, Loading, DialogMessage},
        data(){
            return{
                sending: false,
                loading: true,
                loadFailed: false,
                notFound:false,
                id : null,
                isAdmin : true,
                dialogVars: {
                    title: '',
                    content: '',
                    mode: '',
                    show: false,
                    showTime: 2000
                },
                userData:{
                    username:null,
                    user:null,
                    password:null,
                }
            }
        },
        methods: {
            initForm(id){
                if(parseInt(id) === 1){
                    this.notFound = true;
                    this.loading = false;
                }else {
                    this.loading = false;
                    axios.get(`/api/user/${id}`)
                        .then(res => {
                            this.userData = res.data;
                            if (this.userData.id !== 1) {
                                this.isAdmin = false;
                            }
                            this.loading = false;
                        })
                        .catch(err => {
                            const res = err.response;
                            if (res) {
                                if (res.status === 404) {
                                    this.notFound = true;
                                    this.loading = false;
                                }
                            } else {
                                this.err(err);
                            }
                        })
                    ;
                }
            },
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
            update(id){
                this.sending = true;
                axios.patch(`/api/user/${id}`,this.userData)
                    .then((res) => {
                        this.sending = false;
                        this.showDialog(true, "ثبت موفق","اطلاعات با موفقیت ثبت شد.",'success',2000);
                        this.userData = res.data;
                    })
                    .catch(err => {
                        this.sending = false;
                        this.errorHandling(err);
                    })
                ;
            },
        },
        created() {
            this.id = this.$route.params.id;
            this.initForm(this.id);
        }
    }
</script>

<style scoped>

</style>
