<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JobMetric\BanIp\Enums\TableBanIpFieldTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('ban-ip.tables.ban_ip'), function (Blueprint $table) {
            $table->id();

            $table->ipAddress('ip')->index();
            /**
             * The ip field is used to store the IP address of the banned user.
             */

            $table->string('type')->index();
            /**
             * The type field is used to distinguish different types of banned users.
             *
             * Value: hacker, spammer, bot, another
             * use: @extends TableBanIpFieldTypeEnum
             */

            $table->string('reason')->nullable();
            /**
             * The reason field is used to store the reason for banning the user.
             */

            $table->timestamp('banned_at')->nullable();
            /**
             * The banned_at field is used to store the date and time the user was banned.
             */

            $table->timestamp('expired_at')->nullable();
            /**
             * The expired_at field is used to store the date and time the user's ban will expire.
             */
        });

        cache()->forget('ban-ips');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('ban-ip.tables.ban_ip'));

        cache()->forget('ban-ips');
    }
};
