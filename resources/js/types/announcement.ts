import type { User } from './user';

export interface Announcement {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    excerpt?: string;
    content: string;
    status: 'draft' | 'published' | 'archived';
    is_pinned: boolean;
    end_date?: string;
    published_at?: string;
    user_id: number;
    author?: User;
    created_at?: string;
    updated_at?: string;
}
