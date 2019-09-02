<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <div v-else-if="!loading" id="service-form">
            <h3 class="border-bottom">پروفایل سرویس</h3>
            <div>
                <h5>مشخصات سرویس</h5>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="i-service-name">عنوان سرویس</label>
                        <input @keyup.enter="update(id)" type="text" v-model="serviceData.name" id="i-service-name" class="form-control" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-service-m_line">شماره سر خط (مرد)</label>
                        <input @keyup.enter="update(id)" type="text" v-model="serviceData.m_line" id="i-service-m_line" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-service-w_line">شماره سر خط (زن)</label>
                        <input @keyup.enter="update(id)" type="text" v-model="serviceData.w_line" id="i-service-w_line" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="i-service-ws_address">آدرس وب سرویس</label>
                        <input @keyup.enter="update(id)" type="text" v-model="serviceData.ws_address" id="i-service-ws_address" class="form-control" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-service-ws_username">نام کاربری وب سرویس</label>
                        <input @keyup.enter="update(id)" type="text" v-model="serviceData.ws_username" id="i-service-ws_username" class="form-control" />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-service-ws_password">رمز عبور وب سرویس</label>
                        <input @keyup.enter="update(id)" type="password" v-model="serviceData.ws_password" id="i-service-ws_password" class="form-control" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-service-ws_update_interval">فاصله زمانی به روزرسانی وب سرویس (ثانیه)</label>
                        <input @keyup.enter="update(id)" type="text" v-model="serviceData.ws_update_interval" id="i-service-ws_update_interval" class="form-control" @keypress="isNumber($event)"/>
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
                    <div class="form-group col-md-6">
                        <label for="i-service-demo_first_charge">اعتبار اولیه دمو</label>
                        <input @keyup.enter="update(id)" type="text" v-model="serviceData.demo_first_charge" id="i-service-demo_first_charge" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-service-demo_charge_type_id">نوع اعتبار دمو</label>
                        <select v-model="serviceData.demo_charge_type_id" id="i-service-demo_charge_type_id" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="chargeType in chargeTypes" v-bind:value="chargeType.id">
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
            <br /><br /><br />
            <h5 class="border-bottom">نماد های پیش فرض</h5>
            <div>
                <div class="form-row tiny-margin-b">
                    <button @click="addDS" class="btn btn-info">افزودن نماد</button>
                </div>
                <div v-for="(symbol, index) in serviceData.defaultSymbols" class="form-row">
                    <div class="form-group col-md-6">
                        <label :for="`${index}-dsp`">ترتیب پخش</label>
                        <select type="text" v-model="symbol.symbol_id" :id="`${index}-dsp`" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="symbolItem in symbols" v-bind:value="symbolItem.id">
                                {{symbolItem.fName}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label :for="`${index}-dsp`">ترتیب پخش</label>
                        <select type="text" v-model="symbol.priority" :id="`${index}-dsp`" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="priorityItem in priorities" v-bind:value="priorityItem">
                                {{priorityItem}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <div class="d-none d-md-block"><br /></div>
                        <button @click="removeDS(index)" class="btn btn-danger">حذف</button>
                    </div>
                </div>
            </div>
            <br /><br /><br />
            <h5>پیام های صوتی سرویس</h5>
            <div>
                <table class="table table-striped border-bottom">
                    <tr>
                        <td>خوش آمدگویی مشتری : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_customer_welcome" accept=".mp3" :placeholder="(serviceData.m_customer_welcome === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_customer_welcome" accept=".mp3" :placeholder="(serviceData.w_customer_welcome === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td class="border-right">خوش آمدگویی دمو : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_demo_welcome" accept=".mp3" :placeholder="(serviceData.m_demo_welcome === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_demo_welcome" accept=".mp3" :placeholder="(serviceData.w_demo_welcome === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                    </tr>
                    <tr>
                        <td>شروع منو مشتری : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_customer_menu_start" accept=".mp3" :placeholder="(serviceData.m_customer_menu_start === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_customer_menu_start" accept=".mp3" :placeholder="(serviceData.w_customer_menu_start === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td class="border-right">شروع منو دمو : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_demo_menu_start" accept=".mp3" :placeholder="(serviceData.m_demo_menu_start === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_demo_menu_start" accept=".mp3" :placeholder="(serviceData.w_demo_menu_start === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                    </tr>
                    <tr>
                        <td>اتمام اشتراک مشتری : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_customer_no_charge" accept=".mp3" :placeholder="(serviceData.m_customer_no_charge === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_customer_no_charge" accept=".mp3" :placeholder="(serviceData.w_customer_no_charge === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td class="border-right">اتمام اشتراک دمو : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_demo_no_charge" accept=".mp3" :placeholder="(serviceData.m_demo_no_charge === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_demo_no_charge" accept=".mp3" :placeholder="(serviceData.w_demo_no_charge === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                    </tr>
                    <tr>
                        <td>غیر فعال شدن مشتری : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_customer_inactive" accept=".mp3" :placeholder="(serviceData.m_customer_inactive === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_customer_inactive" accept=".mp3" :placeholder="(serviceData.w_customer_inactive === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td class="border-right">غیر فعال بودن سرویس : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_inactive" accept=".mp3" :placeholder="(serviceData.m_inactive === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_inactive" accept=".mp3" :placeholder="(serviceData.w_inactive === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                    </tr>
                    <tr>
                        <td>فروش یا خرید  : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="filesData.m_sb" accept=".mp3" :placeholder="(serviceData.m_sb === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="filesData.w_sb" accept=".mp3" :placeholder="(serviceData.w_sb === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td class="border-right">اعداد : </td>
                        <td>
                            <span>مرد : </span>
                            <b-form-file class="serv-form" v-model="m_numbers" accept=".zip" :placeholder="(serviceData.m_numbers === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                        <td>
                            <span>زن : </span>
                            <b-form-file class="serv-form" v-model="w_numbers" accept=".zip" :placeholder="(serviceData.w_numbers === 1)?'ثبت شده':'ثبت نشده'"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="form-row">
                <button @click="update(id)" class="btn btn-success" :disabled="sending">ثبت
                    <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
            </div>
            <br />
        </div>
    </div>
</template>

<script>
    import DialogMessage from "../../layout/element/DialogMessage";
    import Loading from "../../layout/element/Loading";
    import {mixins} from "../../../mixins";
    export default {
        name: "ServiceProfile",
        components: {Loading, DialogMessage},
        data(){
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
                serviceData:{
                    name:null,
                    m_line:null,
                    w_line:null,
                    is_active:true,
                    customer_is_free:false,
                    demo_is_free:false,
                    demo_first_charge:null,
                    demo_charge_type_id:null,
                    ws_address:null,
                    ws_username:null,
                    ws_password:null,
                    ws_update_interval:null,
                    user_id:null,
                    defaultSymbols:[]
                },
                filesData : {
                    w_customer_welcome : null,
                    m_customer_welcome : null,
                    w_customer_menu_start : null,
                    m_customer_menu_start : null,
                    w_customer_no_charge : null,
                    m_customer_no_charge : null,
                    w_customer_inactive : null,
                    m_customer_inactive : null,
                    w_demo_welcome : null,
                    m_demo_welcome : null,
                    w_demo_menu_start : null,
                    m_demo_menu_start : null,
                    w_demo_no_charge : null,
                    m_demo_no_charge : null,
                    w_inactive : null,
                    m_inactive : null,
                    w_sb : null,
                    m_sb : null,
                },
                m_numbers : null,
                w_numbers : null,
                priorities : [],
                symbols : [],
                chargeTypes: [],
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
            initForm(id){
                axios.get('/api/charge_type')
                    .then(res => {
                        this.chargeTypes = res.data;
                        axios.get(`/api/service/${id}`)
                            .then((res) => {
                                axios.get('/api/user')
                                    .then(res => {
                                        this.users = res.data;
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
                                this.serviceData = res.data;
                                this.initPriorities(this.serviceData.defaultSymbols.length);
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
                    })
                    .catch(err => {
                        this.err(err);
                    })
                ;
            },
            update(id){
                this.sending = true;
                const raw = this.serviceData;
                const files = this.filesData;
                let formData = new FormData();
                formData.append('_method', 'PATCH');
                formData.append('name', raw.name);
                formData.append('m_line', raw.m_line);
                formData.append('w_line', raw.w_line);
                formData.append('is_active',(raw.is_active === 1 || raw.is_active === true)?1:0);
                formData.append('customer_is_free',(raw.customer_is_free === 1 || raw.customer_is_free === true)?1:0);
                formData.append('demo_is_free',(raw.demo_is_free === 1 || raw.demo_is_free === true)?1:0);
                formData.append('demo_first_charge', raw.demo_first_charge);
                formData.append('demo_charge_type_id', raw.demo_charge_type_id);
                formData.append('ws_address', raw.ws_address);
                formData.append('ws_username', raw.ws_username);
                formData.append('ws_password', raw.ws_password);
                formData.append('ws_update_interval', raw.ws_update_interval);
                formData.append('user_id', raw.user_id);
                for(let item in raw.defaultSymbols){
                    if (!raw.defaultSymbols.hasOwnProperty(item)) continue;
                    formData.append(`default_symbols[${item}][symbol_id]`, JSON.stringify(raw.defaultSymbols[item].symbol_id));
                    formData.append(`default_symbols[${item}][priority]`, JSON.stringify(raw.defaultSymbols[item].priority));
                }
                for(let item in files){
                    if (!files.hasOwnProperty(item)) continue;
                    let data = files[item];
                    if(data !== null){
                        formData.append(item,data);
                    }
                }
                if(this.m_numbers !== null){
                    formData.append('m_numbers',this.m_numbers);
                }
                if(this.w_numbers !== null){
                    formData.append('w_numbers',this.w_numbers);
                }
                axios.post(`/api/service/${id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then((res) => {
                        this.serviceData = res.data;
                        this.sending = false;
                        this.filesData = {
                            w_customer_welcome : null,
                                m_customer_welcome : null,
                                w_customer_menu_start : null,
                                m_customer_menu_start : null,
                                w_customer_no_charge : null,
                                m_customer_no_charge : null,
                                w_customer_inactive : null,
                                m_customer_inactive : null,
                                w_demo_welcome : null,
                                m_demo_welcome : null,
                                w_demo_menu_start : null,
                                m_demo_menu_start : null,
                                w_demo_no_charge : null,
                                m_demo_no_charge : null,
                                w_inactive : null,
                                m_inactive : null,
                                w_sb : null,
                                m_sb : null,
                        };
                        this.m_numbers = null;
                        this.w_numbers = null;
                        this.initPriorities(this.serviceData.defaultSymbols.length);
                        this.showDialog(true, "ثبت موفق","اطلاعات با موفقیت ثبت شد.",'success',2000);
                    })
                    .catch(err => {
                        this.sending = false;
                        this.errorHandling(err);
                    })
                ;
            },
            addDS(){
                this.serviceData.defaultSymbols.push({symbol_id: null, priority: null});
                this.initPriorities(this.serviceData.defaultSymbols.length);
            },
            removeDS(index){
                this.serviceData.defaultSymbols.splice(index, 1);
                this.initPriorities(this.serviceData.defaultSymbols.length);
            },
            initPriorities(count){
                this.priorities = [];
                for(let i = 1;i <= count;i++){
                    this.priorities.push(i);
                }
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
