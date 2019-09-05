<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="customer-form">
            <h3 class="border-bottom">ویرایش واحد</h3>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="i-name">نام</label>
                    {{unitData.name}}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="i-m_file">فایل (مرد)</label><br />
                    <b-form-file class="serv-form" v-model="m_file" accept=".mp3" id="i-m_file" placeholder="انتخاب کنید"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="i-w_file">فایل (زن)</label><br />
                    <b-form-file class="serv-form" v-model="w_file" accept=".mp3" id="i-w_file" placeholder="انتخاب کنید"/>
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
    import Loading from "../../layout/element/Loading";
    import DialogMessage from "../../layout/element/DialogMessage";
    import {mixins} from "../../../mixins";

    export default {
        name: "UnitsProfile",
        components: {DialogMessage, Loading},
        data() {
            return {
                sending: false,
                loading: true,
                loadFailed: false,
                id: null,
                dialogVars: {
                    title: '',
                    content: '',
                    mode: '',
                    show: false,
                    showTime: 2000
                },
                m_file:null,
                w_file:null,
                unitData:{
                    name:null,
                },
            }
        },
        methods:{
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
            initForm(id){
                axios.get(`/api/unit/${id}`)
                    .then(res => {
                        this.unitData = res.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.err(err);
                    })
                    .catch(err => {
                        this.err(err);
                    })
                ;
            },
            save(){
                this.sending = true;
                let formData = new FormData();
                formData.append('unit_id',this.id);
                if(this.m_file !== null){
                    formData.append('m_file',this.m_file);
                }
                if(this.w_file !== null){
                    formData.append('w_file',this.w_file);
                }
                axios.post('/api/unit',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(() => {
                        this.sending = false;
                        this.showDialog(true, "ثبت موفق","اطلاعات با موفقیت ثبت شد.",'success',2000);
                    })
                    .catch(err => {
                        this.sending = false;
                        this.errorHandling(err);console.log(err.response)
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
