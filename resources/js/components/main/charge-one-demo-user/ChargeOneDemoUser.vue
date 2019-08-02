<template>
    <div>
        <dialog-message :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="cod-form">
            <h3 class="border-bottom">افزایش اعتبار تکی کاربران دمو</h3>
            <div>
            </div>
        </div>
    </div>
</template>

<script>
    import DialogMessage from "../../layout/element/DialogMessage";
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";
    export default {
        name: "ChargeOneDemoUser",
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
            }
        },
        methods:{
            err(err){
                this.loadFailed = true;
                this.loading = false;
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
                    }else{
                        this.showDialog(true, "خطای سرور",'مشکلی در سرور به وجود آمده است','danger',0);
                    }
                }else {
                    this.showDialog(true, "خطای ارتباط با سرور","ارتباط با سرور انجام نشد",'danger',0);
                }
            },
        },
        created() {
            this.initForm();
        }
    }
</script>

<style scoped>

</style>
