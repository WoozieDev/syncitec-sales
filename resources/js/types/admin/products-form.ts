import type { OptionItem } from '@/types/admin/products';

export type ProductFormPayload = {
    category_id: number;
    brand_id: number | null;
    sku: string;
    name: string;
    description: string | null;
    price: string; // en soles (UI)
    currency: string; // "PEN"
    stock_on_hand: number;
    is_active: boolean;
};

export type ProductFormProps = {
    categories: OptionItem[];
    brands: OptionItem[];
    initial?: Partial<ProductFormPayload>;
    submitLabel?: string;
};
