<?php

namespace JobMetric\BanIp\Enums;

use JobMetric\PackageCore\Enums\EnumToArray;

/**
 * @method static HACKER()
 * @method static SPAMMER()
 * @method static BOT()
 * @method static ANOTHER()
 */
enum TableBanIpFieldTypeEnum : string {
    use EnumToArray;

    case HACKER = 'hacker';
    case SPAMMER = 'spammer';
    case BOT = 'bot';
    case ANOTHER = 'another';
}
