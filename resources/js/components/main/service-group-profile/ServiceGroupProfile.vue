<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="customer-form">
            <h3 class="border-bottom">گروه سرویس جدید</h3>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="i-name">نام</label>
                    <input @keyup.enter="save" type="text" v-model="sgData.name" id="i-name" class="form-control"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="i-m_file">فایل (مرد)</label><br />
                    <b-form-file class="serv-form" v-model="m_file" accept=".mp3" id="i-m_file" :placeholder="(sgData.m_file === 1)?'ثبت شده':'ثبت نشده'"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="i-w_file">فایل (زن)</label><br />
                    <b-form-file class="serv-form" v-model="w_file" accept=".mp3" id="i-w_file" :placeholder="(sgData.w_file === 1)?'ثبت شده':'ثبت نشده'"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-1">
                    <label for="i-is_active">فعال</label>
                    <input type="checkbox" v-model="sgData.is_active" id="i-is_active" class="form-control"/>
                </div>
            </div>
            <h4>نماد ها</h4>
            <div class="form-row tiny-margin-b">
                <button @click="addS" class="btn btn-info">افزودن نماد</button>
            </div>
            <div v-for="(symbol, index) in sgData.symbols" class="form-row">
                <div class="col-md-5">
                    <label :for="`${index}-sgsID`">نماد</label>
                    <select v-model="symbol.id" :id="`${index}-sgsID`" class="form-control" >
                        <option value="null" disabled selected>انتخاب کنید</option>
                        <option v-for="symbolItem in symbols" v-bind:value="symbolItem.id">
                            {{symbolItem.fName}}
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label :for="`${index}-sgsID`">ترتیب پخش</label>
                    <select v-model="symbol.priority" :id="`${index}-sgsID`" class="form-control" >
                        <option value="null" disabled selected>انتخاب کنید</option>
                        <option v-for="priority in priorities" v-bind:value="priority">
                            {{priority}}
                        </option>
                    </select>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2 tiny-margin-t">
                    <button @click="removeS(index)" class="btn btn-danger tiny-margin-t">حذف</button>
                </div>
            </div><br />
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
        name: "ServiceGroupProfile",
        components: {Loading, DialogMessage},
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
                sgData:{
                    name:null,
                    m_file:null,
                    w_file:null,
                    is_active:true,
                    symbols:[]
                },
                priorities: [],
                symbols: []
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
            initForm(id){
                axios.get(`/api/service_group/${id}`)
                    .then(res => {
                        this.sgData = res.data;
                        for(let item in this.sgData.symbols){
                            if (!this.sgData.symbols.hasOwnProperty(item)) continue;
                            this.sgData.symbols[item].priority = this.sgData.symbols[item].pivot.priority
                        }
                        this.initPriorities(this.sgData.symbols.length);
                        axios.get('api/symbol')
                            .then((res) => {
                                this.symbols = res.data;
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
            },
            addS(){
                this.sgData.symbols.push({id: null, priority: null});
                this.initPriorities(this.sgData.symbols.length);
            },
            removeS(index){
                this.sgData.symbols.splice(index, 1);
                this.initPriorities(this.sgData.symbols.length);
            },
            initPriorities(count){
                this.priorities = [];
                for(let i = 1;i <= count;i++){
                    this.priorities.push(i);
                }
            },
            save(){
                this.sending = true;
                const raw = this.sgData;
                let formData = new FormData();
                formData.append('_method', 'PATCH');
                formData.append('name',raw.name);
                formData.append('is_active',(raw.is_active === 1 || raw.is_active === true)?1:0);
                for(let item in raw.symbols){
                    if (!raw.symbols.hasOwnProperty(item)) continue;
                    formData.append(`symbols[${item}][id]`, JSON.stringify(raw.symbols[item].id));
                    formData.append(`symbols[${item}][priority]`, JSON.stringify(raw.symbols[item].priority));
                }
                if(this.m_file !== null){
                    formData.append('m_file',this.m_file);
                }
                if(this.w_file !== null){
                    formData.append('w_file',this.w_file);
                }
                axios.post(`/api/service_group/${this.id}`,formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((res) => {
                        this.sgData = res.data;
                        for(let item in this.sgData.symbols){
                            if (!this.sgData.symbols.hasOwnProperty(item)) continue;
                            this.sgData.symbols[item].priority = this.sgData.symbols[item].pivot.priority
                        }
                        this.sending = false;
                        this.m_file = null;
                        this.w_file = null;
                        this.showDialog(true, "ثبت موفق","اطلاعات با موفقیت ثبت شد.",'success',2000);
                    })
                    .catch(err => {
                        this.sending = false;
                        this.errorHandling(err);
                    })
                ;
            }
        },
        created() {
            this.id = this.$route.params.id;
            this.initForm(this.id);
        }
    }
</script>

<style scoped>

</style>
