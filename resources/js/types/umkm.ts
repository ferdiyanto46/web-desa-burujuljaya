export interface UmkmCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface Umkm {
    id: number;
    ulid: string;
    category_id: number;
    name: string;
    slug: string;
    owner_name: string;
    description: string;
    address: string;
    phone?: string;
    is_active: boolean;
    category?: UmkmCategory;
    created_at?: string;
    updated_at?: string;
}
