export interface GalleryPhoto {
    id: number;
    ulid: string;
    gallery_id: number;
    image_path: string;
    image_url: string;
    caption?: string | null;
    alt_text?: string | null;
    photographer?: string | null;
    file_size?: number | null;
    width?: number | null;
    height?: number | null;
    mime?: string | null;
    extension?: string | null;
    disk?: string | null;
    sort_order: number;
    relationships?: {
        gallery?: any; // Replace with Gallery type when imported to avoid circular if needed
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
