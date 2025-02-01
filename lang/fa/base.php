<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base Ban Ip Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Ban Ip for
    | various messages that we need to display to the user.
    |
    */

    "validation" => [
        "ban_ip_exist" => ":attribute (:ip) در نوع (:type) وجود دارد.",
        "ban_ip_not_found" => "آی‌پی مسدود شده یافت نشد.",
        "banned_at_bigger_expired_at" => "زمان مسدودیت بیشتر از زمان انقضا است.",
        "expired_at_bigger_banned_at" => "زمان انقضا کمتر از زمان مسدودیت است.",
    ],

    "messages" => [
        "created" => "ban ip  با موفقیت ایجاد شد.",
        "updated" => "ban ip  با موفقیت بروزرسانی شد.",
        "deleted" => "ban ip  با موفقیت حذف شد.",
    ],

    "view" => [
        "title" => "مسدود کردن آی‌پی",
        "description" => "به هیچ وجه!",
    ]

];
