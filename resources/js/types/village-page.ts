export interface VillagePage {
    id: number;
    ulid: string;
    page_type: string;
    page_type_label: string;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string;
    featured_image_url: string | null;
    status: string;
    status_label: string;
    published_at: string | null;
    is_pinned: boolean;
    sort_order: number;
    meta_title: string | null;
    meta_description: string | null;
    canonical_url: string | null;
    author?: {
        id: number;
        name: string;
    };
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
}
