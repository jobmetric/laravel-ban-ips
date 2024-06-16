<?php

namespace JobMetric\BanIp\Tests;

use JobMetric\BanIp\Enums\TableBanIpFieldTypeEnum;
use JobMetric\BanIp\Facades\BanIp;
use Tests\BaseDatabaseTestCase as BaseTestCase;

class BanIpTest extends BaseTestCase
{
    private string $ip = '127.0.0.1';
    private string $ip_temp = '127.0.0.2';

    public function testStore(): void
    {
        // Store a ban ip by filling only the ip field
        $banIp = BanIp::store([
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
        ]);

        $this->assertIsArray($banIp);
        $this->assertTrue($banIp['ok']);
        $this->assertEquals(201, $banIp['status']);
        $this->assertIsInt($banIp['data']->id);
        $this->assertDatabaseHas(config('ban-ip.tables.ban_ip'), [
            'id' => $banIp['data']->id,
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
        ]);

        // Store a ban ip existing in the database
        $banIp = BanIp::store([
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
            'reason' => 'Hacker'
        ]);

        $this->assertIsArray($banIp);
        $this->assertFalse($banIp['ok']);
        $this->assertEquals(422, $banIp['status']);

        // Store a ban ip by filling all fields
        $banIp = BanIp::store([
            'ip' => $this->ip_temp,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
            'reason' => 'Hacker',
            'banned_at' => now(),
            'expired_at' => now()->addDay(),
        ]);

        $this->assertIsArray($banIp);
        $this->assertTrue($banIp['ok']);
        $this->assertEquals(201, $banIp['status']);
        $this->assertIsInt($banIp['data']->id);
        $this->assertDatabaseHas(config('ban-ip.tables.ban_ip'), [
            'id' => $banIp['data']->id,
            'ip' => $this->ip_temp,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
            'reason' => 'Hacker',
            'banned_at' => now()->format('Y-m-d H:i:s'),
            'expired_at' => now()->addDay()->format('Y-m-d H:i:s'),
        ]);
    }

    public function testUpdate(): void
    {
        // Update a ban ip by filling only the ip field
        $banIp = BanIp::store([
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
        ]);

        $this->assertIsArray($banIp);
        $this->assertTrue($banIp['ok']);
        $this->assertEquals(201, $banIp['status']);
        $this->assertIsInt($banIp['data']->id);
        $this->assertDatabaseHas(config('ban-ip.tables.ban_ip'), [
            'id' => $banIp['data']->id,
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
        ]);

        $banIp = BanIp::update($banIp['data']->id, [
            'type' => TableBanIpFieldTypeEnum::SPAMMER(),
        ]);

        $this->assertIsArray($banIp);
        $this->assertTrue($banIp['ok']);
        $this->assertEquals(200, $banIp['status']);
        $this->assertIsInt($banIp['data']->id);
        $this->assertDatabaseHas(config('ban-ip.tables.ban_ip'), [
            'id' => $banIp['data']->id,
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::SPAMMER(),
        ]);

        // Update a ban ip by filling all fields
        $banIp = BanIp::update($banIp['data']->id, [
            'type' => TableBanIpFieldTypeEnum::SPAMMER(),
            'reason' => 'Spammer',
            'banned_at' => now(),
            'expired_at' => now()->addDay(),
        ]);

        $this->assertIsArray($banIp);
        $this->assertTrue($banIp['ok']);
        $this->assertEquals(200, $banIp['status']);
        $this->assertIsInt($banIp['data']->id);
        $this->assertDatabaseHas(config('ban-ip.tables.ban_ip'), [
            'id' => $banIp['data']->id,
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::SPAMMER(),
            'reason' => 'Spammer',
            'banned_at' => now()->format('Y-m-d H:i:s'),
            'expired_at' => now()->addDay()->format('Y-m-d H:i:s'),
        ]);
    }

    public function testDelete(): void
    {
        // Delete a ban ip
        $banIp = BanIp::store([
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
        ]);

        $this->assertIsArray($banIp);
        $this->assertTrue($banIp['ok']);
        $this->assertEquals(201, $banIp['status']);
        $this->assertIsInt($banIp['data']->id);
        $this->assertDatabaseHas(config('ban-ip.tables.ban_ip'), [
            'id' => $banIp['data']->id,
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
        ]);

        $banIpDeleteResult = BanIp::delete($banIp['data']->id);

        $this->assertIsArray($banIpDeleteResult);
        $this->assertTrue($banIpDeleteResult['ok']);
        $this->assertEquals(200, $banIpDeleteResult['status']);
        $this->assertDatabaseMissing(config('ban-ip.tables.ban_ip'), [
            'id' => $banIp['data']->id,
            'ip' => $this->ip,
            'type' => TableBanIpFieldTypeEnum::HACKER(),
        ]);
    }
}
