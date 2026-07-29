export interface ComplaintCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}

export interface ComplaintAttachment {
    id: number;
    complaint_id: number;
    file_path: string;
    original_filename: string;
    mime_type: string;
    created_at?: string;
    updated_at?: string;
}

export interface Complaint {
    id: number;
    ulid: string;
    category_id: number;
    reporter_name: string;
    reporter_email?: string;
    reporter_phone?: string;
    title: string;
    body: string;
    is_anonymous: boolean;
    status: 'pending' | 'processing' | 'resolved' | 'rejected';
    resolved_at?: string;
    category?: ComplaintCategory;
    attachments?: ComplaintAttachment[];
    created_at?: string;
    updated_at?: string;
}
