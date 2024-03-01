<?php

namespace JobMetric\BanIp\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JobMetric\BanIp\Enums\TableBanIpFieldTypeEnum;
use JobMetric\BanIp\Rules\BanIpExistRule;

class StoreBanIpRequest extends FormRequest
{
    public array $data = [];

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
        if(empty($this->data)) {
            $type = $this->input('type');
        } else {
            $type = $this->data['type'] ?? null;
        }

        return [
            'ip' => [
                'string',
                new BanIpExistRule($type)
            ],
            'type' => 'string|in:' . implode(',', TableBanIpFieldTypeEnum::values()),
            'reason' => 'string',
            'banned_at' => 'date',
            'expired_at' => 'date|after:banned_at'
        ];
    }

    /**
     * Set data for validation
     *
     * @param array $data
     * @return static
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
