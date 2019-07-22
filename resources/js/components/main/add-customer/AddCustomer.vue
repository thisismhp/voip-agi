<template>
    <div id="customer-form">
        <h3 class="border-bottom">مشتری جدید</h3>
        <div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="i-customer-name">نام و نام خانوادگی</label>
                    <input type="text" v-model="customerData.name" id="i-customer-name" class="form-control" />
                </div>
                <div class="form-group col-md-6">
                    <label for="i-customer-code">کد مشتری</label>
                    <input type="text" v-model="customerData.code" id="i-customer-code" class="form-control" disabled/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="i-customer-nation-code">کد ملی</label>
                    <input type="text" v-model="customerData.name" id="i-customer-nation-code" class="form-control" />
                </div>
                <div class="form-group col-md-6">
                    <label for="i-customer-birth-day">تاریخ تولد</label>
                    <date-picker locale="fa" format="jYYYY/jMM/jDD" placeholder="YYYY/MM/DD" :editable="true" :auto-submit="true" type="date" v-model="customerData.birth_date" id="i-customer-birth-day"/>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="i-customer-province">استان</label>
                    <select type="text" v-model="customerData.province_id" id="i-customer-province" class="form-control" @change="onProvinceSelect($event.target.value)">
                        <option value="null" disabled selected>انتخاب کنید</option>
                        <option v-for="province in provinces" v-bind:value="province.id">
                            {{province.name}}
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="i-customer-city">شهر</label>
                    <select type="text" v-model="customerData.city_id" id="i-customer-city" class="form-control">
                        <option value="null" disabled selected>انتخاب کنید</option>
                        <option v-for="city in cities" v-bind:value="city.id">
                            {{city.name}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-row border-bottom">
                <div class="form-group col-md-6">
                    <label for="i-customer-address">آدرس</label>
                    <input type="text" v-model="customerData.address" id="i-customer-address" class="form-control" />
                </div>
                <div class="form-group col-md-6">
                    <label for="i-customer-phone-number">شماره تماس</label>
                    <input type="text" v-model="customerData.phone_number" id="i-customer-phone-number" class="form-control" />
                </div>
            </div>
            <div id="customer-form-numbers" class="customer-form-numbers">
                <h4>شماره تلفن ها</h4>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="i-customer-phone-type">عنوان</label>
                        <input type="text" v-model="customerData.numbers" id="i-customer-phone-type" class="form-control" />
                    </div>
                    <div class="form-group col-md-3">
                        <label for="i-customer-phone-phone-number">شماره تلفن</label>
                        <input type="text" v-model="customerData.numbers" id="i-customer-phone-phone-number" class="form-control" />
                    </div>
                    <div class="form-group col-md-3">
                        <label for="i-customer-phone-charge-type">نوع اعتبار</label>
                        <input type="text" v-model="customerData.numbers" id="i-customer-phone-charge-type" class="form-control" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="i-customer-phone-active">فعال</label>
                        <br />
                        <input type="checkbox" v-model="customerData.numbers" id="i-customer-phone-active"/>
                    </div>
                </div>
                <button class="btn btn-info">افزودن شماره تلفن</button>
                <button class="btn btn-success">ثبت</button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import VuePersianDatetimePicker from 'vue-persian-datetime-picker';
    export default {
        name: "AddCustomer",
        components: {
            datePicker: VuePersianDatetimePicker
        },
        data(){
            return {
                customerData:{
                    name:null,
                    code:null,
                    nation_code:null,
                    birth_date:null,
                    province_id:null,
                    city_id:null,
                    address:null,
                    phone_number:null,
                    numbers:[]
                },
                provinces:[],
                cities:[]
            }
        },
        methods:{
            initForm(){
                axios.get('/api/province')
                    .then(res => {
                        this.provinces = res.data;
                    })
                    .catch(err => {
                        console.log(err)
                    })
                ;
            },
            onProvinceSelect(id){
                if(id != null){
                    this.customerData.city_id = null;
                    this.cities = this.provinces[id - 1].cities;
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
