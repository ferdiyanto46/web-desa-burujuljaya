export interface VillageMission {
    id: number;
    village_profile_id: number;
    content: string;
    sort_order: number;
    created_at?: string;
    updated_at?: string;
}

export interface VillageProfile {
    id: number;
    village_name: string;
    village_code?: string;
    district: string;
    regency: string;
    province: string;
    postal_code?: string;
    address: string;
    phone?: string;
    email?: string;
    website_url?: string;
    history?: string;
    vision?: string;
    area_hectares?: string;
    latitude?: string;
    longitude?: string;
    established_year?: number;
    logo_path?: string;
    missions?: VillageMission[];
    created_at?: string;
    updated_at?: string;
}

export interface Apparatus {
    id: number;
    ulid: string;
    position_id: number;
    name: string;
    nip?: string;
    photo_path?: string;
    is_active: boolean;
    created_at?: string;
    updated_at?: string;
}
