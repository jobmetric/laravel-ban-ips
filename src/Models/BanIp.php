<?php

namespace JobMetric\BanIp\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * JobMetric\BanIp\Models\BanIp
 *
 * @property int id
 * @property string ip
 * @property string type
 * @property string reason
 * @property string banned_at
 * @property string expired_at
 */
class BanIp extends Model
{
    use HasFactory;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'ip',
        'type',
        'reason',
        'banned_at',
        'expired_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ip' => 'string',
        'type' => 'string',
        'reason' => 'string',
        'banned_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    public function getTable()
    {
        return config('ban-ip.tables.ban_ip', parent::getTable());
    }

    /**
     * Scope a query to only include categories of a given type.
     *
     * @param Builder $query
     * @param string $type
     *
     * @return Builder
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Set the scope of a query so that it only determines
     * the expiration dates that have been rejected.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOfExpired(Builder $query): Builder
    {
        return $query->where('expired_at', '<', now());
    }
}
