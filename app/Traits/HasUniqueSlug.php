<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

trait HasUniqueSlug
{
    /**
     * Boot the trait to generate and update slugs automatically.
     */
    protected static function bootHasUniqueSlug(): void
    {
        static::creating(static function (Model $model): void {
            $slugSource = $model->getSlugSourceColumn();
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug((string) $model->{$slugSource});
            }
        });

        static::updating(static function (Model $model): void {
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
        $hasSoftDeletes = in_array(SoftDeletes::class, class_uses_recursive(static::class), true);

        $checkExists = function (string $checkSlug) use ($hasSoftDeletes): bool {
            $query = static::where('slug', $checkSlug);
            if ($hasSoftDeletes) {
                $query->withTrashed();
            }

            return $query->exists();
        };

        while ($checkExists($slug)) {
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
            if (str_contains($e->getMessage(), "{$tableName}_slug_unique") || str_contains($e->getMessage(), 'Duplicate entry')) {
                $slugSource = $this->getSlugSourceColumn();
                $this->slug = static::generateUniqueSlug((string) $this->{$slugSource}.'-'.Str::random(5));

                return parent::save($options);
            }

            throw $e;
        }
    }

    /**
     * Define the source column for slug generation.
     */
    public function getSlugSourceColumn(): string
    {
        return property_exists($this, 'slugSourceColumn') ? $this->slugSourceColumn : 'title';
    }
}
