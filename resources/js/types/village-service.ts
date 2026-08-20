import type { User } from './user';

export interface VillageService {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    excerpt?: string;
    description: string;
    featured_image_path?: string;
    featured_image_url?: string;
    
    requirements?: string;
    procedures?: string;
    processing_time?: string;
    cost?: string;
    service_hours?: string;
    
    contact_person?: string;
    phone?: string;
    email?: string;
    
    downloadable_form_path?: string;
    downloadable_form_name?: string;
    downloadable_form_disk?: string;
    downloadable_form_size?: number;
    downloadable_form_mime?: string;
    downloadable_form_extension?: string;
    downloadable_form_url?: string;
    
    status: 'draft' | 'published' | 'archived';
    is_pinned: boolean;
    sort_order: number;
    published_at?: string;
    
    meta?: {
        meta_title?: string;
        meta_description?: string;
        og_image_path?: string;
    };
    
    timestamps?: {
        created_at?: string;
        updated_at?: string;
        deleted_at?: string;
    };
    
    permissions?: {
        view: boolean;
        update: boolean;
        delete: boolean;
    };
    
    links?: {
        self?: string;
        edit?: string;
    };
    
    user_id?: number;
    author?: User;
}
