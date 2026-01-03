import type { PaginationLink } from '@/types/pagination';

export type CategoryParent = {
    id: number;
    name: string;
};

export type CategoryListItem = {
    id: number;
    name: string;
    slug: string;
    is_active: boolean;
    sort_order: number;
    parent: CategoryParent | null;
    children_count: number;
    created_at?: string | null;
    deleted_at?: string | null;
};

export type CategoryFilters = {
    search?: string;
    parent?: string | number | null;
    status?: '' | 'active' | 'inactive';
};

export type CategoriesPagination = {
    data: CategoryListItem[];
    links: PaginationLink[];
};
