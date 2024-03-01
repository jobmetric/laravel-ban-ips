<?php

namespace JobMetric\BanIp\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JobMetric\BanIp\Enums\TableBanIpFieldTypeEnum;

class UpdateBanIpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'string|in:' . implode(',', TableBanIpFieldTypeEnum::values()) . '|sometimes',
            'reason' => 'string|nullable|sometimes',
            'banned_at' => 'date|nullable|sometimes',
            'expired_at' => 'date|nullable|sometimes'
        ];
    }
}
