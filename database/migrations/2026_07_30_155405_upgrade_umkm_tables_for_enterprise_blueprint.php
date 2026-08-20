<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename table umkm to umkms if it exists
        if (Schema::hasTable('umkm') && ! Schema::hasTable('umkms')) {
            Schema::rename('umkm', 'umkms');
        }

        // 2. Add ulid to umkm_categories
        Schema::table('umkm_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('umkm_categories', 'ulid')) {
                $table->char('ulid', 26)->unique()->after('id')->nullable();
            }
        });

        // 3. Upgrade umkms table
        Schema::table('umkms', function (Blueprint $table) {
            if (! Schema::hasColumn('umkms', 'ulid')) {
                $table->char('ulid', 26)->unique()->after('id')->nullable();
            }
            if (Schema::hasColumn('umkms', 'name') && ! Schema::hasColumn('umkms', 'title')) {
                $table->renameColumn('name', 'title');
            }
            if (Schema::hasColumn('umkms', 'cover_image') && ! Schema::hasColumn('umkms', 'featured_image_path')) {
                $table->renameColumn('cover_image', 'featured_image_path');
            }

            if (Schema::hasIndex('umkms', 'umkm_public_idx')) {
                $table->dropIndex('umkm_public_idx');
            }

            if (Schema::hasColumn('umkms', 'is_active')) {
                $table->dropColumn('is_active');
            }

            // Add new missing fields
            if (! Schema::hasColumn('umkms', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (! Schema::hasColumn('umkms', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (! Schema::hasColumn('umkms', 'google_maps_url')) {
                $table->string('google_maps_url', 500)->nullable();
            }
            if (! Schema::hasColumn('umkms', 'whatsapp')) {
                $table->string('whatsapp', 20)->nullable();
            }
            if (! Schema::hasColumn('umkms', 'email')) {
                $table->string('email')->nullable();
            }
            if (! Schema::hasColumn('umkms', 'website')) {
                $table->string('website', 500)->nullable();
            }
            if (! Schema::hasColumn('umkms', 'instagram')) {
                $table->string('instagram')->nullable();
            }
            if (! Schema::hasColumn('umkms', 'facebook')) {
                $table->string('facebook')->nullable();
            }
            if (! Schema::hasColumn('umkms', 'operating_hours')) {
                $table->string('operating_hours')->nullable();
            }
            if (! Schema::hasColumn('umkms', 'status')) {
                $table->string('status', 20)->default('draft');
            }
            if (! Schema::hasColumn('umkms', 'published_at')) {
                $table->timestamp('published_at')->nullable();
            }
            if (! Schema::hasColumn('umkms', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (! Schema::hasColumn('umkms', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false);
            }
            if (! Schema::hasColumn('umkms', 'view_count')) {
                $table->unsignedBigInteger('view_count')->default(0);
            }
            if (! Schema::hasColumn('umkms', 'featured_order')) {
                $table->integer('featured_order')->default(0);
            }

            // New composite index
            $table->index(['status', 'category_id', 'deleted_at'], 'umkms_public_idx');
            $table->index('is_featured');
            $table->index('published_at');
        });

        // Generate ULIDs for existing categories if any
        DB::table('umkm_categories')->whereNull('ulid')->get()->each(function ($cat) {
            DB::table('umkm_categories')->where('id', $cat->id)->update([
                'ulid' => (string) Str::ulid(),
            ]);
        });

        // Generate ULIDs for existing umkms if any
        DB::table('umkms')->whereNull('ulid')->get()->each(function ($item) {
            DB::table('umkms')->where('id', $item->id)->update([
                'ulid' => (string) Str::ulid(),
            ]);
        });

        // 4. Create umkm_photos table
        Schema::create('umkm_photos', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('umkm_id')->constrained('umkms')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->integer('order_column')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm_photos');

        Schema::table('umkms', function (Blueprint $table) {
            $table->dropIndex('umkms_public_idx');
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['published_at']);

            $table->dropColumn([
                'latitude', 'longitude', 'google_maps_url',
                'whatsapp', 'email', 'website', 'instagram', 'facebook',
                'operating_hours', 'status', 'published_at',
                'is_featured', 'is_pinned', 'view_count', 'featured_order',
            ]);

            $table->boolean('is_active')->default(true);
            $table->renameColumn('title', 'name');
            $table->renameColumn('featured_image_path', 'cover_image');
        });

        Schema::rename('umkms', 'umkm');

        Schema::table('umkm_categories', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });
    }
};
