export interface ProductCategory {
    id: number;
    ulid: string;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface ProductPhoto {
    id: number;
    ulid: string;
    image_path: string;
    caption: string | null;
    alt_text: string | null;
    sort_order: number;
}

export interface Product {
    id: number;
    ulid: string;
    category_id: number;
    umkm_id: number;
    title: string;
    slug: string;
    excerpt?: string;
    description: string;
    sku?: string;
    price: number;
    stock?: number;
    unit?: string;
    weight?: number;
    featured_image_path?: string;
    status: string;
    published_at?: string;
    is_featured: boolean;
    is_pinned: boolean;
    view_count: number;
    meta_title?: string;
    meta_description?: string;
    canonical_url?: string;
    category?: ProductCategory;
    photos?: ProductPhoto[];
    umkm?: {
        id: number;
        ulid: string;
        title: string;
        slug?: string;
    };
    created_at?: string;
    updated_at?: string;
}
