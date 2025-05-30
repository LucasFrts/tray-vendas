export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};

export interface Order {
  id: string;
  amount: number;
  date: string; // ISO 8601 format, e.g., '2025-05-29T12:00:00Z'
  seller_id: string;
}

export interface Seller{
    id: string;
    name: string;
}