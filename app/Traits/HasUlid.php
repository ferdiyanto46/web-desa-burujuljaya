<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUlid
{
    /**
     * Boot the trait to automatically generate a ULID.
     */
    protected static function bootHasUlid(): void
    {
        static::creating(static function (self $model): void {
            if (empty($model->ulid)) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
