<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

$models = collect(File::allFiles(app_path('Models')))
    ->map(function ($file) {
        return 'App\\Models\\'.str_replace('.php', '', $file->getFilename());
    })
    ->filter(function ($class) {
        return class_exists($class) && is_subclass_of($class, Model::class);
    });

foreach ($models as $class) {
    try {
        $model = new $class;
        $table = $model->getTable();
        if (! Schema::hasTable($table)) {
            continue;
        }

        $columns = Schema::getColumnListing($table);
        $fillable = $model->getFillable();

        echo "Model: $class\n";

        // Find missing fillable (excluding guarded/auto-managed fields)
        $expectedNonFillable = ['id', 'ulid', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'published_at', 'password', 'remember_token', 'email_verified_at', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];
        $unfillableColumns = array_diff($columns, $fillable, $expectedNonFillable);

        if (! empty($unfillableColumns)) {
            echo '  Warning: Columns exist in DB but not in fillable: '.implode(', ', $unfillableColumns)."\n";
        }

        $missingInDb = array_diff($fillable, $columns);
        if (! empty($missingInDb)) {
            echo '  ERROR: Columns in fillable but MISSING in DB: '.implode(', ', $missingInDb)."\n";
        }
    } catch (Exception $e) {
    }
}
