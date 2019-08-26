<template>
    <div class="login">
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <div class="login-box col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin">
                <div class="card-body">
                    <h5 class="card-title text-center">ورود</h5>
                    <div class="form-label-group">
                        <label for="username">نام کاربری</label>
                        <input @keyup.enter="login" v-model="loginData.username" type="text" id="username" name="username" class="form-control inputUsername" autofocus />
                    </div>

                    <div class="form-label-group tiny-margin-t">
                        <label for="password">رمزعبور</label>
                        <input @keyup.enter="login" v-model="loginData.password" type="password" id="password" name="password" class="form-control inputPassword" />
                    </div>
                    <button @click="login" class="btn btn-lg btn-primary btn-block tiny-margin-t" type="submit" :disabled="sending">ورود
                        <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import DialogMessage from "../element/DialogMessage";
    import qs from 'qs';

    export default {
        name: "Auth",
        components: {DialogMessage},
        data(){
            return {
                sending: false,
                dialogVars:{
                    title:'',
                    content:'',
                    mode:'',
                    show:false,
                    showTime:2000

                },
                loginData:{
                    grant_type: 'password',
                    client_id: 1,
                    client_secret: 'voip.vue.secret',
                    username: null,
                    password: null
                }
            }
        },
        methods:{
            showDialog(show, title, content, mode, showTime){
                this.dialogVars.show=show;
                this.dialogVars.title=title;
                this.dialogVars.content=content;
                this.dialogVars.mode=mode;
                this.dialogVars.showTime=showTime;
            },
            login(){
                if(this.loginData.username === null || this.loginData.password === null){
                    this.showDialog(true, "ورود ناموفق","وارد کردن نام کاربری و رمز عبور الزامی است",'danger',2000);
                }else {
                    this.sending = true;
                    const data = this.loginData;
                    const url = '/oauth/token';
                    const options = {
                        method: 'POST',
                        headers: {'content-type': 'application/x-www-form-urlencoded'},
                        data: qs.stringify(data),
                        url,
                    };
                    axios(options)
                        .then((res) => {
                            localStorage.setItem('token', res.data.access_token);
                            this.$store.state.authCheck = true;
                            this.sending = false;
                            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                        })
                        .catch((err) => {
                            this.$store.state.authCheck = false;
                            this.sending = false;
                            if(err.response) {
                                if (err.response.status === 401) {
                                    this.showDialog(true, "ورود ناموفق","نام کاربری یا رمزعبور اشتباه است",'danger',1000);
                                }else {
                                    this.showDialog(true, "ورود ناموفق","مشکلی در سرور به وجود آمده است",'danger',1000);
                                }
                            }else{
                                this.showDialog(true, "ورود ناموفق","مشکلی در سرور به وجود آمده است",'danger',1000);
                            }
                        })
                    ;
                }
            }
        }
    }
</script>

<style scoped>

</style>
