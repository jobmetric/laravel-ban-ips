<?php

namespace JobMetric\BanIp\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use JobMetric\BanIp\Models\BanIp;

class BanIpExistRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        private readonly string $type
    )
    {
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value == 0) {
            return;
        }

        if (!BanIp::query()->where('ip', $value)->where('type', $this->type)->exists()) {
            $fail(__('ban-ip::base.validation.ban_ip_exist', [
                'attribute' => $attribute,
                'ip' => $value,
                'type' => $this->type
            ]));
        }
    }
}
