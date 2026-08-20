import type { PublicationStatus } from './status';

export interface UmkmCategory {
    id: number;
    ulid: string;
    name: string;
    slug: string;
    description: string | null;
}

export interface UmkmPhoto {
    id: number;
    ulid: string;
    file_path: string;
    caption: string | null;
    order: number;
}

export interface Umkm {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    owner_name: string | null;
    description: string | null;
    featured_image_path: string | null;
    
    // Location
    address: string | null;
    coordinates: {
        latitude: string | null;
        longitude: string | null;
    } | null;
    latitude: string | null;
    longitude: string | null;
    google_maps_url: string | null;
    
    // Contact
    phone: string | null;
    whatsapp: string | null;
    email: string | null;
    website: string | null;
    instagram: string | null;
    facebook: string | null;
    
    operating_hours: string | null;
    
    // Flags
    status: PublicationStatus;
    is_featured: boolean;
    featured_order: number;
    is_pinned: boolean;
    view_count: number;
    published_at: string | null;
    created_at: string | null;
    updated_at: string | null;
    
    // Relations
    category?: UmkmCategory;
    author?: {
        id: number;
        name: string;
    };
    photos?: UmkmPhoto[];
    photo_count: number;
}
