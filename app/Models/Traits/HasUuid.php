<?php

declare(strict_types = 1);

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
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
}
