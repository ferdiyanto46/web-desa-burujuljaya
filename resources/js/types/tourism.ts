export interface TourismCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface Tourism {
    id: number;
    ulid: string;
    category_id: number;
    name: string;
    slug: string;
    description: string;
    location: string;
    latitude?: string;
    longitude?: string;
    image_path?: string;
    is_active: boolean;
    category?: TourismCategory;
    created_at?: string;
    updated_at?: string;
}
