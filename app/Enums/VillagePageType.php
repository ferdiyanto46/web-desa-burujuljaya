<?php

declare(strict_types=1);

namespace App\Enums;

enum VillagePageType: string
{
    case History = 'history';
    case VisionMission = 'vision-mission';
    case Organization = 'organization';
    case Government = 'government';
    case Geography = 'geography';
    case Demography = 'demography';
    case Potential = 'potential';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::History => 'Sejarah',
            self::VisionMission => 'Visi & Misi',
            self::Organization => 'Struktur Organisasi',
            self::Government => 'Pemerintahan',
            self::Geography => 'Geografis',
            self::Demography => 'Demografi',
            self::Potential => 'Potensi Desa',
            self::Custom => 'Kustom (Lainnya)',
        };
    }
}
