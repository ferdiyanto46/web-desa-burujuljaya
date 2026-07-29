export interface ProductCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface Product {
    id: number;
    ulid: string;
    category_id: number;
    umkm_id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    image_path?: string;
    is_active: boolean;
    category?: ProductCategory;
    created_at?: string;
    updated_at?: string;
}
