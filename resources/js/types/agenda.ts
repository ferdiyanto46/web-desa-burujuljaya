import type { User } from './user';

export interface Agenda {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    content: string;
    location: string;
    location_url?: string;
    featured_image_path?: string;
    start_datetime: string;
    end_datetime?: string;
    status: 'draft' | 'published' | 'archived';
    is_cancelled: boolean;
    event_status: 'upcoming' | 'ongoing' | 'finished' | 'cancelled';
    published_at?: string;
    user_id: number;
    author?: User;
    created_at?: string;
    updated_at?: string;
}
