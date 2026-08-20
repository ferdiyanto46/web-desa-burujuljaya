export interface ContactInformation {
    id: number;
    ulid: string;
    title: string;
    slug: string;
    excerpt: string | null;
    description: string | null;
    address: string | null;
    google_maps_embed: string | null;
    google_maps_url: string | null;
    phone: string | null;
    mobile: string | null;
    whatsapp: string | null;
    email: string | null;
    website: string | null;
    facebook: string | null;
    instagram: string | null;
    youtube: string | null;
    tiktok: string | null;
    office_hours: string | null;
    emergency_contact: string | null;
    featured_image_path: string | null;
    status: 'draft' | 'published' | 'archived';
    published_at: string | null;
    is_pinned: boolean;
    sort_order: number;
    meta: Record<string, unknown> | null;
    user_id: number;
    author?: {
        id: number;
        name: string;
    };
    timestamps: {
        created_at: string;
        updated_at: string;
    };
    permissions?: {
        view: boolean;
        update: boolean;
        delete: boolean;
    };
    links?: {
        self: string;
        public: string;
    };
}
