import type { User } from './user';

export interface ArticleCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface Article {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    excerpt?: string;
    content: string;
    featured_image_path?: string;
    status: 'draft' | 'published' | 'archived';
    is_featured: boolean;
    views: number;
    published_at?: string;
    meta_title?: string;
    meta_description?: string;
    og_image_path?: string;
    category_id: number;
    user_id: number;
    author?: User;
    category?: ArticleCategory;
    created_at?: string;
    updated_at?: string;
}
