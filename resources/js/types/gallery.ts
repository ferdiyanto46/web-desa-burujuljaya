import type { GalleryPhoto } from './gallery-photo';
import type { User } from './index';

export interface Gallery {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    description?: string | null;
    featured_image_path?: string | null;
    featured_image_url?: string | null;
    status: 'draft' | 'published' | 'archived';
    published_at?: string | null;
    is_pinned: boolean;
    photos_count?: number;
    relationships?: {
        author?: User;
        photos?: GalleryPhoto[];
    };
    permissions?: {
        update: boolean;
        delete: boolean;
    };
    timestamps?: {
        created_at?: string;
        updated_at?: string;
    };
}
