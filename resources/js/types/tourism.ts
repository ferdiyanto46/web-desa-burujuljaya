import type { PublicationStatus } from './status';

export interface TourismCategory {
    id: number;
    ulid: string;
    name: string;
    slug: string;
    description: string | null;
}

export interface TourismFacility {
    id: number;
    ulid: string;
    name: string;
    icon: string;
}

export interface TourismPhoto {
    id: number;
    ulid: string;
    file_url: string;
    caption: string | null;
    order: number;
}

export interface Tourism {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    excerpt: string | null;
    description: string | null;
    featured_image_url: string | null;
    
    // Location
    address: string | null;
    coordinates: {
        latitude: string | null;
        longitude: string | null;
    };
    google_maps_url: string | null;
    
    // Info
    opening_hours: string | null;
    ticket_price: string | null;
    contact_person: string | null;
    phone: string | null;
    website: string | null;
    
    // Flags
    status: PublicationStatus;
    is_featured: boolean;
    featured_order: number;
    is_pinned: boolean;
    view_count: number;
    published_at: string | null;
    created_at: string | null;
    updated_at: string | null;
    
    // SEO
    seo: {
        meta_title: string | null;
        meta_description: string | null;
        og_image_url: string | null;
        canonical_url: string | null;
    };
    
    // Relations
    category?: TourismCategory;
    author?: {
        id: number;
        name: string;
    };
    facilities?: TourismFacility[];
    gallery?: TourismPhoto[];
    photo_count: number;
}
