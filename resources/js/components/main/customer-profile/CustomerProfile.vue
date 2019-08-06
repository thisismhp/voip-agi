<template>
    <div>
        <DialogMessage :show="dialogVars.show" :title="dialogVars.title" :content="dialogVars.content" :mode="dialogVars.mode" :show-time="dialogVars.showTime" @show="dialogVars.show = false"/>
        <Loading v-if="loading" />
        <div class="failed center-align" v-else-if="loadFailed">
            <button @click="reload" class="btn btn-warning">تلاش مجدد</button>
        </div>
        <NotFound not-found-title="مشتری" v-else-if="notFound" />
        <div v-else-if="!loading" id="customer-profile">
            <h3 class="border-bottom">پرونده مشتری</h3>
            <br />
            <h5 class="border-bottom">اعتبار</h5>
            <div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <span class="profile-title">اعتبار تعدادی</span>
                        <span> : </span>
                        <span class="profile-value">{{customerData.time_charge}}</span>
                    </div>
                    <div class="form-group col-md-5">
                        <span class="profile-title">اعتبار بازه ای تا</span>
                        <span> : </span>
                        <span class="profile-value">{{toJalaali(customerData.date_charge,' ','-')}}</span>
                    </div>
                    <div class="form-group col-md-2">
                        <router-link class="btn btn-secondary" :to="`/charge-one-customer?id=${customerData.id}`" >افزایش اعتبار</router-link>
                    </div>

                </div>
            </div>
            <br />
            <h5 class="border-bottom">اطلاعات مشتری</h5>
            <div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-customer-name">نام و نام خانوادگی</label>
                        <input type="text" v-model="customerData.name" id="i-customer-name" class="form-control" />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-customer-code">کد مشتری</label>
                        <input type="text" v-model="customerData.id" id="i-customer-code" class="form-control" disabled/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-customer-nation-code">کد ملی</label>
                        <input type="text" v-model="customerData.nation_code" id="i-customer-nation-code" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-customer-birth-day">تاریخ تولد</label>
                        <date-picker locale="fa" format="jYYYY/jMM/jDD" placeholder="YYYY/MM/DD" :editable="true" :auto-submit="true" type="date" v-model="customerData.birth_date" id="i-customer-birth-day"/>
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="i-customer-province">استان</label>
                        <select v-model="customerData.province_id" id="i-customer-province" class="form-control" @change="onProvinceSelect($event.target.value)">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="province in provinces" v-bind:value="province.id">
                                {{province.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="i-customer-city">شهر</label>
                        <select v-model="customerData.city_id" id="i-customer-city" class="form-control">
                            <option value="null" disabled selected>انتخاب کنید</option>
                            <option v-for="city in cities" v-bind:value="city.id">
                                {{city.name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row border-bottom">
                    <div class="form-group col-md-9">
                        <label for="i-customer-address">آدرس</label>
                        <input type="text" v-model="customerData.address" id="i-customer-address" class="form-control" />
                    </div>
                    <div class="form-group col-md-3">
                        <label for="i-customer-phone-number">شماره تماس</label>
                        <input type="text" v-model="customerData.phone_number" id="i-customer-phone-number" class="form-control" @keypress="isNumber($event)"/>
                    </div>
                </div>
                <div id="customer-form-numbers" class="customer-form-numbers">
                    <h4>شماره تلفن ها</h4>
                    <div class="form-row tiny-margin-b">
                        <button @click="addNumber" class="btn btn-info">افزودن شماره تلفن</button>
                    </div>
                    <div v-for="(number, index) in customerData.numbers" class="form-row">
                        <div class="form-group col-md-3">
                            <label :for="`${index}-customer-phone-type`">عنوان</label>
                            <select v-model="number.phone_number_type_id" :id="`${index}-customer-phone-type`" class="form-control">
                                <option value="null" disabled selected>انتخاب کنید</option>
                                <option v-for="phoneNumberType in phoneNumberTypes" v-bind:value="phoneNumberType.id">
                                    {{phoneNumberType.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label :for="`${index}-customer-phone-phone-number`">شماره تلفن</label>
                            <input type="text" v-model="number.phone_number" :id="`${index}-customer-phone-phone-number`" class="form-control" @keypress="isNumber($event)"/>
                        </div>
                        <div class="form-group col-md-3">
                            <label :for="`${index}-customer-phone-charge-type`">نوع اعتبار</label>
                            <select v-model="number.charge_type_id" :id="`${index}-customer-phone-charge-type`" class="form-control" >
                                <option value="null" disabled selected>انتخاب کنید</option>
                                <option v-for="chargeType in chargeTypes" v-bind:value="chargeType.id">
                                    {{chargeType.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-1 d-none d-md-block"></div>
                        <div class="form-group col-md-1">
                            <label :for="`${index}-customer-phone-active`">فعال</label>
                            <br />
                            <input type="checkbox" v-model="number.is_active" :id="`${index}-customer-phone-active`"/>
                        </div>
                        <div class="col-md-1">
                            <div class="d-none d-md-block"><br /></div>
                            <button @click="removeNumber(index)" class="btn btn-danger">حذف</button>
                        </div>
                    </div>
                    <div class="form-row">
                        <button @click="update(id)" class="btn btn-success" :disabled="sending">ثبت
                            <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import VuePersianDatetimePicker from 'vue-persian-datetime-picker';
    import Loading from "../../layout/element/Loading";
    import DialogMessage from "../../layout/element/DialogMessage";
    import {mixins} from "../../../mixins";
    import NotFound from "../../layout/element/NotFound";
    export default {
        name: "CustomerProfile",
        components: {
            NotFound,
            DialogMessage,
            Loading,
            datePicker: VuePersianDatetimePicker
        },
        data() {
            return {
                sending: false,
                loading: true,
                loadFailed:false,
                notFound:false,
                dialogVars:{
                    title:'',
                    content:'',
                    mode:'',
                    show:false,
                    showTime:2000

                },
                provincesIsLoad: false,
                id: null,
                customerData:[],
                provinces: [],
                cities: [],
                phoneNumberTypes: [],
                chargeTypes: [],
            }
        },
        methods: {
            isNumber : mixins.isNumber,
            toJalaali : mixins.toJalaaliJustDate,
            err(err){
                this.loadFailed = true;
                this.loading = false;
                if(err.response.status === 401){
                    this.$store.state.authCheck = false;
                }
                return err;
            },
            reload(){
                this.loading = true;
                this.loadFailed = false;
                this.load(this.id);
            },
            showDialog(show, title, content, mode, showTime){
                this.dialogVars.show=show;
                this.dialogVars.title=title;
                this.dialogVars.content=content;
                this.dialogVars.mode=mode;
                this.dialogVars.showTime=showTime;
            },
            initForm() {
                axios.get('/api/province')
                    .then(res => {
                        this.provinces = res.data;
                        this.cities = this.provinces[this.customerData.province_id - 1].cities;
                        axios.get('/api/charge_type')
                            .then(res => {
                                this.chargeTypes = res.data;
                                axios.get('/api/phone_number_type')
                                    .then(res => {
                                        this.phoneNumberTypes = res.data;
                                        this.loading = false;
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
                    })
                    .catch(err => {
                        this.err(err);
                    })
                ;
            },
            onProvinceSelect(id) {
                if (id != null) {
                    this.customerData.city_id = null;
                    this.cities = this.provinces[id - 1].cities;
                }
            },
            addNumber() {
                this.customerData.numbers.push({
                    phone_number_type_id: null,
                    phone_number: null,
                    charge_type_id: null,
                    is_active: true
                });
            },
            removeNumber(index) {
                this.customerData.numbers.splice(index, 1)
            },
            load(id){
                axios.get(`/api/customer/${id}`)
                    .then(res => {
                        this.initForm();
                        this.customerData = res.data;
                    })
                    .catch(err => {
                        const res = err.response;
                        if(res){
                            if(res.status === 404){
                                this.notFound = true;
                                this.loading = false;
                            }
                        }else {
                            this.err(err);
                        }
                    })
                ;
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
            update(id){
                this.sending = true;
                axios.patch(`/api/customer/${id}`,this.customerData)
                    .then(res => {
                        this.customerData = res.data;
                        this.sending = false;
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
            this.id = this.$route.params.id;
            this.load(this.id);
        }
    }
</script>

<style scoped>

</style>
