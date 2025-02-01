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
        "ban_ip_exist" => "The :attribute (:ip) exist in type (:type).",
        "ban_ip_not_found" => "The ban ip not found.",
        "banned_at_bigger_expired_at" => "Banned time is greater than expired time.",
        "expired_at_bigger_banned_at" => "The expired time is less than the banned time.",
    ],

    "messages" => [
        "created" => "The ban ip was created successfully.",
        "updated" => "The ban ip was updated successfully.",
        "deleted" => "The ban ip was deleted successfully.",
    ],

    "view" => [
        "title" => "Ban Ip",
        "description" => "No way!"
    ]

];
