import type { User } from './user';

export interface GalleryAlbum {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    description?: string;
    is_active: boolean;
    user_id: number;
    author?: User;
    photos?: GalleryPhoto[];
    created_at?: string;
    updated_at?: string;
}

export interface GalleryPhoto {
    id: number;
    album_id: number;
    title: string;
    image_path: string;
    sort_order: number;
    created_at?: string;
    updated_at?: string;
}

export interface Video {
    id: number;
    ulid: string;
    title: string;
    youtube_id: string;
    description?: string;
    is_active: boolean;
    user_id: number;
    author?: User;
    created_at?: string;
    updated_at?: string;
}
