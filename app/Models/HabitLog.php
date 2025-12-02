<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HabitLog extends Model
{
    /** @use HasFactory<\Database\Factories\HabitLogFactory> */
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $log): void {
            $log->uuid ??= (string) Str::uuid();
        });

        static::updating(function (self $log): void {
            $log->uuid = $log->getOriginal('uuid');
        });
    }

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }
}
