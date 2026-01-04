import type { PaginationLink } from '@/types/pagination';

export type BrandListItem = {
    id: number;
    name: string;
    slug: string;
    is_active: boolean;
    sort_order: number;
    deleted_at?: string | null;
};

export type BrandFilters = {
    search?: string;
    status?: '' | 'active' | 'inactive';
};

export type BrandsPagination = {
    data: BrandListItem[];
    links: PaginationLink[];
};
