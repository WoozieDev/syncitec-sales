import type { PaginationLink } from '@/types/pagination';

export type OptionItem = { id: number; name: string };

export type ProductListItem = {
    id: number;
    sku: string;
    name: string;
    slug: string;
    is_active: boolean;
    currency: string;
    price_amount: number;
    price_decimal: number;
    stock_on_hand: number;
    category: OptionItem | null;
    brand: OptionItem | null;
    primary_image: { path: string } | null;
    deleted_at?: string | null;
};

export type ProductFilters = {
    search?: string;
    category?: string | number | null;
    brand?: string | number | null;
    status?: '' | 'active' | 'inactive';
    trashed?: 'without' | 'with' | 'only';
};

export type ProductsPagination = {
    data: ProductListItem[];
    links: PaginationLink[];
};
