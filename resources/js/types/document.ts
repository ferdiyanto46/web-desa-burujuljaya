import type { User } from './user';

export interface DocumentCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface DocumentPermissions {
    view: boolean;
    update: boolean;
    delete: boolean;
}

export interface DocumentLinks {
    self: string | null;
    edit: string | null;
    download: string | null;
}

export interface DocumentTimestamps {
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;
}

export interface Document {
    id: number;
    ulid: string;
    
    title: string;
    slug: string;
    description?: string;
    is_pinned: boolean;
    
    file_name: string;
    file_path: string;
    file_url: string;
    mime: string;
    extension: string;
    file_size: number;
    download_count: number;
    
    status: 'draft' | 'published' | 'archived';
    published_at?: string;
    
    category_id: number;
    category?: DocumentCategory;
    user_id: number;
    author?: User;
    
    timestamps: DocumentTimestamps;
    permissions: DocumentPermissions;
    links: DocumentLinks;
}
