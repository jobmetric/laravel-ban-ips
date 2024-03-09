<?php

namespace JobMetric\BanIp\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JobMetric\BanIp\Enums\TableBanIpFieldTypeEnum;
use JobMetric\BanIp\Models\BanIp;

/**
 * @extends Factory<BanIp>
 */
class BanIpFactory extends Factory
{
    protected $model = BanIp::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ip' => $this->faker->ipv4,
            'type' => $this->faker->randomElement(TableBanIpFieldTypeEnum::values()),
            'reason' => $this->faker->sentence,
            'banned_at' => $this->faker->dateTimeThisYear->format('Y-m-d H:i:s'),
            'expired_at' => $this->faker->dateTimeThisYear->format('Y-m-d H:i:s')
        ];
    }

    /**
     * set ip
     *
     * @param string $ip
     *
     * @return static
     */
    public function setIp(string $ip): static
    {
        return $this->state(fn(array $attributes) => [
            'ip' => $ip
        ]);
    }

    /**
     * set type
     *
     * @param string $type
     *
     * @return static
     */
    public function setParent(string $type): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => $type
        ]);
    }

    /**
     * set reason
     *
     * @param string $reason
     *
     * @return static
     */
    public function setReason(string $reason): static
    {
        return $this->state(fn(array $attributes) => [
            'reason' => $reason
        ]);
    }

    /**
     * set banned_at
     *
     * @param string $banned_at
     *
     * @return static
     */
    public function setBannedAt(string $banned_at): static
    {
        return $this->state(fn(array $attributes) => [
            'banned_at' => $banned_at
        ]);
    }

    /**
     * set expired_at
     *
     * @param string $expired_at
     *
     * @return static
     */
    public function setExpiredAt(string $expired_at): static
    {
        return $this->state(fn(array $attributes) => [
            'expired_at' => $expired_at
        ]);
    }
}
