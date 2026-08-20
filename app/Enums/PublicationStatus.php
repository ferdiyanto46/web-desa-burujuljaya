<?php

declare(strict_types=1);

namespace App\Enums;

enum PublicationStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Scheduled = 'scheduled';
    case Archived = 'archived';

    /**
     * Get the human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Konsep',
            self::Published => 'Terbit',
            self::Scheduled => 'Terjadwal',
            self::Archived => 'Diarsipkan',
        };
    }

    /**
     * Get the badge color class for the UI.
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::Draft => 'bg-gray-100 text-gray-800',
            self::Published => 'bg-green-100 text-green-800',
            self::Scheduled => 'bg-blue-100 text-blue-800',
            self::Archived => 'bg-yellow-100 text-yellow-800',
        };
    }
}
