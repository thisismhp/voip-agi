<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"         => ":attribute باید پذیرفته شده باشد.",
    "active_url"       => "آدرس :attribute معتبر نیست",
    "after"            => ":attribute باید تاریخی بعد از :date باشد.",
    "alpha"            => ":attribute باید شامل حروف الفبا باشد.",
    "alpha_dash"       => ":attribute باید شامل حروف الفبا و عدد و خظ تیره(-) باشد.",
    "alpha_num"        => ":attribute باید شامل حروف الفبا و عدد باشد.",
    "array"            => ":attribute باید شامل آرایه باشد.",
    "before"           => ":attribute باید تاریخی قبل از :date باشد.",
    "between"          => array(
        "numeric" => ":attribute باید بین :min و :max باشد.",
        "file"    => ":attribute باید بین :min و :max کیلوبایت باشد.",
        "string"  => ":attribute باید بین :min و :max کاراکتر باشد.",
        "array"   => ":attribute باید بین :min و :max آیتم باشد.",
    ),
    "boolean"          => "The :attribute field must be true or false",
    "confirmed"        => ":attribute با تاییدیه مطابقت ندارد.",
    "date"             => ":attribute یک تاریخ معتبر نیست.",
    "date_format"      => ":attribute با الگوی :format مطاقبت ندارد.",
    "different"        => ":attribute و :other باید متفاوت باشند.",
    "digits"           => ":attribute باید :digits رقم باشد.",
    "digits_between"   => ":attribute باید بین :min و :max رقم باشد.",
    "email"            => "فرمت :attribute معتبر نیست.",
    "exists"           => ":attribute انتخاب شده، معتبر نیست.",
    "image"            => ":attribute باید تصویر باشد.",
    "in"               => ":attribute انتخاب شده، معتبر نیست.",
    "integer"          => ":attribute باید نوع داده ای عددی (integer) باشد.",
    "ip"               => ":attribute باید IP آدرس معتبر باشد.",
    "max"              => array(
        "numeric" => ":attribute نباید بزرگتر از :max باشد.",
        "file"    => ":attribute نباید بزرگتر از :max کیلوبایت باشد.",
        "string"  => ":attribute نباید بیشتر از :max کاراکتر باشد.",
        "array"   => ":attribute نباید بیشتر از :max آیتم باشد.",
    ),
    "mimes"       => ":attribute باید یکی از فرمت های :values باشد.",
    'mimetypes'   =>        ":attribute باید یکی از فرمت های :values باشد.",
    "min"              => array(
        "numeric" => ":attribute نباید کوچکتر از :min باشد.",
        "file"    => ":attribute نباید کوچکتر از :min کیلوبایت باشد.",
        "string"  => ":attribute نباید کمتر از :min کاراکتر باشد.",
        "array"   => ":attribute نباید کمتر از :min آیتم باشد.",
    ),
    "not_in"           => ":attribute انتخاب شده، معتبر نیست.",
    "numeric"          => ":attribute باید شامل عدد باشد.",
    "regex"            => ":attribute یک فرمت معتبر نیست",
    "required"         => "فیلد :attribute الزامی است",
    "required_if"      => "فیلد :attribute هنگامی که :other برابر با :value است، الزامیست.",
    "required_with"    => ":attribute الزامی است زمانی که :values موجود است.",
    "required_with_all"=> ":attribute الزامی است زمانی که :values موجود است.",
    "required_without" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "required_without_all" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "same"             => ":attribute و :other باید مانند هم باشند.",
    "size"             => array(
        "numeric" => ":attribute باید برابر با :size باشد.",
        "file"    => ":attribute باید برابر با :size کیلوبایت باشد.",
        "string"  => ":attribute باید برابر با :size کاراکتر باشد.",
        "array"   => ":attribute باسد شامل :size آیتم باشد.",
    ),
    "timezone"         => "The :attribute must be a valid zone.",
    "unique"           => ":attribute قبلا انتخاب شده است.",
    "url"              => "فرمت آدرس :attribute اشتباه است.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [

        "name" => "نام",
        "username" => "نام کاربری",
        "email" => "پست الکترونیکی",
        "first_name" => "نام",
        "last_name" => "نام خانوادگی",
        "password" => "رمز عبور",
        "password_confirmation" => "تاییدیه ی رمز عبور",
        "city" => "شهر",
        "country" => "کشور",
        "address" => "آدرس",
        "phone" => "تلفن",
        "mobile" => "تلفن همراه",
        "age" => "سن",
        "sex" => "جنسیت",
        "gender" => "جنسیت",
        "day" => "روز",
        "month" => "ماه",
        "year" => "سال",
        "hour" => "ساعت",
        "minute" => "دقیقه",
        "second" => "ثانیه",
        "title" => "عنوان",
        "text" => "متن",
        "content" => "محتوا",
        "description" => "توضیحات",
        "excerpt" => "گلچین کردن",
        "date" => "تاریخ",
        "time" => "زمان",
        "available" => "موجود",
        "size" => "اندازه",
        "nation_code" => 'کد ملی',
        "birth_date" => 'تاریخ تولد',
        "province_id" => 'استان',
        "city_id" => 'شهر',
        "phone_number" => 'شماره تماس',
        "destination_type" => 'نوع مقصد (کاربر)',
        "state" => "وضعیت",
        "value" => "مقدار",
        "charge_type_id" => "نوع اعتبار",
        "m_line" => "شماره سر خط (مرد)",
        "w_line" => "شماره سر خط (زن)",
        "ws_address" => "آدرس وب سرویس",
        "ws_username" => "نام کاربری وب سرویس",
        "ws_password" => "رمز عبور وب سرویس",
        "ws_update_interval" => "فاصله زمانی به روزرسانی وب سرویس (ثانیه)",
        "user_id" => "کاربر اپراتور",
        "m_file" => "فایل (مرد)",
        "w_file" => "فایل (زن)",
        "m_numbers" => "اعداد (مرد)",
        "w_numbers" => "اعداد (زن)",
        "m_customer_welcome" => "خوش آمدگویی مشتری (مرد)",
        "w_customer_welcome" => "خوش آمدگویی مشتری (زن)",
        "m_customer_menu_start" => "شروع منو مشتری (مرد)",
        "w_customer_menu_start" => "شروع منو مشتری (زن)",
        "m_customer_no_charge" => "اتمام اشتراک مشتری (مرد)",
        "w_customer_no_charge" => "اتمام اشتراک مشتری (زن)",
        "m_customer_inactive" => "غیر فعال شدن مشتری (مرد)",
        "w_customer_inactive" => "غیر فعال شدن مشتری (زن)",
        "m_demo_welcome" => "خوش آمدگویی دمو (مرد)",
        "w_demo_welcome" => "خوش آمدگویی دمو (زن)",
        "m_demo_menu_start" => "شروع منو دمو (مرد)",
        "w_demo_menu_start" => "شروع منو دمو (زن)",
        "m_demo_no_charge" => "اتمام اشتراک دمو (مرد)",
        "w_demo_no_charge" => "اتمام اشتراک دمو (زن)",
        "m_inactive" => "غیر فعال بودن سرویس (مرد)",
        "w_inactive" => "غیر فعال بودن سرویس (زن)",
        "m_sb" => "فروش یا خرید (مرد)",
        "w_sb" => "فروش یا خرید (زن)",
        "customer_is_free" => "رایگان برای مشتری",
        "demo_is_free" => "رایگان برای دمو",
        "demo_first_charge" => "اعتبار اولیه دمو",
        "demo_charge_type_id" => "نوع اعتبار دمو",
    ],

];
