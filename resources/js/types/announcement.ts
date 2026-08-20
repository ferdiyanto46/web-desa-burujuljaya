export interface Announcement {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string;
    featured_image_url: string | null;
    status: string;
    is_pinned: boolean;
    published_at: string | null;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    author?: {
        id: number;
        name: string;
    };
    permissions: {
        view: boolean;
        update: boolean;
        delete: boolean;
        restore: boolean;
        force_delete: boolean;
    };
    links: {
        edit: string;
        update: string;
        destroy: string;
        restore: string;
        force_destroy: string;
    };
}
