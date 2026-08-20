export interface Faq {
    id: number;
    ulid: string;
    question: string;
    slug: string;
    answer: string;
    category: string | null;
    sort_order: number;
    is_featured: boolean;
    status: 'draft' | 'published' | 'archived';
    published_at: string | null;
    view_count: number;
    meta_title: string | null;
    meta_description: string | null;
    canonical_url: string | null;
    author: {
        name: string;
    };
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
}
