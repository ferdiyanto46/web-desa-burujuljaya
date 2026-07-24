<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Application settings stored as key-value pairs.
 *
 * Settings are organized into named groups:
 *   general     → basic site information
 *   seo         → search engine optimization metadata
 *   social      → social media profile URLs
 *   appearance  → visual customization (colors, fonts, footer)
 *   email       → SMTP configuration (private; some values encrypted)
 *   maintenance → maintenance mode configuration
 *
 * ⚠️  CRITICAL SECURITY RULES:
 *
 * 1. DO NOT read $setting->value directly for encrypted settings.
 *    Always use SettingService::get() which handles automatic decryption
 *    via Crypt::decryptString().
 *
 * 2. DO NOT return all settings to the frontend. Always filter via
 *    SettingService::forPublic() or the `public()` scope to prevent
 *    leaking SMTP passwords, API keys, and other private configuration.
 *
 * 3. DO NOT mass-assign settings from raw request input without a
 *    Form Request that validates and whitelists the allowed fields.
 *
 * Key naming convention: `{group}.{name}` — e.g. `seo.meta_title`
 *
 * @property int $id
 * @property string $key Format: group.name
 * @property string|null $value Raw value — may be Crypt-encrypted
 * @property string $type string | integer | boolean | json | text
 * @property string $group general | seo | social | email | appearance | maintenance
 * @property string $label
 * @property string|null $description
 * @property array|null $options JSON decoded choices for select/radio inputs
 * @property bool $is_public
 * @property bool $is_editable
 * @property bool $is_encrypted
 * @property int $sort_order
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
final class Setting extends Model
{
    /**
     * Attributes that may be set via fill() or create().
     *
     * All attributes are fillable here because seeding and SettingService
     * (both trusted code) need to create/update full setting records.
     *
     * Protection against mass assignment via user input is enforced at the
     * controller level via Form Requests, not via $fillable restrictions.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'options',
        'is_public',
        'is_editable',
        'is_encrypted',
        'sort_order',
    ];

    /**
     * Attribute type casts.
     *
     * Note: `value` is intentionally NOT cast here. It remains as a raw string
     * because SettingService is responsible for:
     *   - Decrypting when is_encrypted = true
     *   - Casting to the correct PHP type based on the `type` column
     *   (e.g., "true" string → true boolean, "42" string → 42 integer)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_public' => 'boolean',
            'is_editable' => 'boolean',
            'is_encrypted' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Query Scopes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Scope: only publicly-accessible, non-encrypted settings.
     *
     * Use this scope for any query that returns settings to the frontend
     * without authentication. The double condition (is_public AND NOT is_encrypted)
     * adds a safety net: even if is_public is accidentally set to true on an
     * encrypted setting, this scope will exclude it.
     *
     * @param  Builder<Setting>  $query
     * @return Builder<Setting>
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query
            ->where('is_public', true)
            ->where('is_encrypted', false);
    }

    /**
     * Scope: settings that are editable via the admin UI.
     *
     * Settings with is_editable = false (system-only) should render as
     * read-only or hidden fields in the admin settings panel.
     *
     * @param  Builder<Setting>  $query
     * @return Builder<Setting>
     */
    public function scopeEditable(Builder $query): Builder
    {
        return $query->where('is_editable', true);
    }

    /**
     * Scope: settings belonging to a specific group, ordered by sort_order.
     *
     * This is the primary scope for rendering a group's settings in the admin panel.
     * Example: Setting::forGroup('seo')->get()
     *
     * @param  Builder<Setting>  $query
     * @return Builder<Setting>
     */
    public function scopeForGroup(Builder $query, string $group): Builder
    {
        return $query
            ->where('group', $group)
            ->orderBy('sort_order');
    }
}
