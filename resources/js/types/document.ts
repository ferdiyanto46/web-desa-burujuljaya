export interface DocumentCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface Document {
    id: number;
    ulid: string;
    category_id: number;
    title: string;
    description?: string;
    file_path: string;
    file_extension: string;
    file_size_bytes: number;
    is_public: boolean;
    downloads: number;
    category?: DocumentCategory;
    created_at?: string;
    updated_at?: string;
}
