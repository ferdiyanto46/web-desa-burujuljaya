<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

trait HasUniqueSlug
{
    /**
     * Boot the trait to generate and update slugs automatically.
     */
    protected static function bootHasUniqueSlug(): void
    {
        static::creating(static function (self $model): void {
            $slugSource = $model->getSlugSourceColumn();
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug((string) $model->{$slugSource});
            }
        });

        static::updating(static function (self $model): void {
            $slugSource = $model->getSlugSourceColumn();
            if ($model->isDirty($slugSource) && empty($model->slug)) {
                $model->slug = static::generateUniqueSlug((string) $model->{$slugSource});
            }
        });
    }

    /**
     * Generate a unique slug for the given string.
     */
    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->withTrashed()->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Override save to handle concurrent unique slug violations safely.
     *
     * @param  array<string, mixed>  $options
     */
    public function save(array $options = []): bool
    {
        try {
            return parent::save($options);
        } catch (UniqueConstraintViolationException $e) {
            $tableName = $this->getTable();
            if (str_contains($e->getMessage(), "{$tableName}_slug_unique")) {
                $slugSource = $this->getSlugSourceColumn();
                $this->slug = static::generateUniqueSlug((string) $this->{$slugSource});

                return parent::save($options);
            }
            throw $e;
        }
    }

    /**
     * Define the source column for slug generation.
     * Override this method in the model if the source column is not 'title'.
     */
    public function getSlugSourceColumn(): string
    {
        return 'title';
    }
}
