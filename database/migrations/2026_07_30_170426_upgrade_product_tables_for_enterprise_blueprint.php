<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Uid\Ulid;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Upgrade product_categories
        if (! Schema::hasColumn('product_categories', 'ulid')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $table->char('ulid', 26)->after('id')->nullable();
            });

            // Backfill ULIDs
            DB::table('product_categories')->orderBy('id')->chunk(100, function ($categories) {
                foreach ($categories as $category) {
                    DB::table('product_categories')
                        ->where('id', $category->id)
                        ->update(['ulid' => (string) Ulid::generate()]);
                }
            });

            Schema::table('product_categories', function (Blueprint $table) {
                $table->char('ulid', 26)->nullable(false)->unique()->change();
            });
        }

        // 2. Upgrade products table
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->renameColumn('cover_image', 'featured_image_path');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('slug');
            $table->string('sku')->nullable()->after('description');
            $table->integer('stock')->default(0)->after('price');
            $table->decimal('weight', 8, 2)->nullable()->after('stock');

            $table->string('status', 20)->default('draft')->after('unit');
            $table->timestamp('published_at')->nullable()->after('status');
            $table->boolean('is_featured')->default(false)->after('published_at');
            $table->boolean('is_pinned')->default(false)->after('is_featured');
            $table->unsignedInteger('view_count')->default(0)->after('is_pinned');

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
        });

        // Migrate is_active to status
        if (Schema::hasColumn('products', 'is_active')) {
            DB::table('products')->where('is_active', 1)->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasIndex('products', 'products_public_idx')) {
                    $table->dropIndex('products_public_idx');
                }
                $table->dropColumn('is_active');
            });
        }

        // 3. Create product_photos
        Schema::create('product_photos', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image_path', 500);
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_photos');

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(false);
            $table->renameColumn('title', 'name');
            $table->renameColumn('featured_image_path', 'cover_image');
        });

        DB::table('products')->where('status', 'published')->update([
            'is_active' => true,
        ]);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'excerpt',
                'sku',
                'stock',
                'weight',
                'status',
                'published_at',
                'is_featured',
                'is_pinned',
                'view_count',
                'meta_title',
                'meta_description',
                'canonical_url',
            ]);
        });

        if (Schema::hasColumn('product_categories', 'ulid')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $table->dropColumn('ulid');
            });
        }
    }
};
