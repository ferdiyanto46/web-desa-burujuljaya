export interface Setting {
    id: number;
    key: string;
    value: string;
    type: 'string' | 'boolean' | 'integer' | 'json';
    group: string;
    description?: string;
    created_at?: string;
    updated_at?: string;
}
