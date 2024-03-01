<?php

namespace JobMetric\BanIp\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JobMetric\BanIp\Rules\BanIpExistRule;

class UpdateBanIpRequest extends FormRequest
{
    public string|null $type = null;
    public int|null $ban_ip_id = null;
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
        if (is_null($this->ban_ip_id)) {
            $ban_ip_id = $this->route()->parameter('ban-ip')->id;
        } else {
            $ban_ip_id = $this->ban_ip_id;
        }

        if (empty($this->data)) {
            $type = $this->input('type');
        } else {
            $type = $this->type ?? null;
        }

        return [
            'ip' => [
                'string',
                'sometimes',
                new BanIpExistRule($type, $ban_ip_id)
            ],
            'type' => 'string|sometimes',
            'reason' => 'string|sometimes',
            'banned_at' => 'date|sometimes',
            'expired_at' => 'date|after:banned_at|sometimes'
        ];
    }

    /**
     * Set type for validation
     *
     * @param string $type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set ban ip id for validation
     *
     * @param int $ban_ip_id
     * @return static
     */
    public function setBanIpId(int $ban_ip_id): static
    {
        $this->ban_ip_id = $ban_ip_id;

        return $this;
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
