<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import ProductForm from "@/pages/admin/products/partials/ProductForm.vue";

import type { OptionItem } from "@/types/admin/products";
import type { ProductFormPayload } from "@/types/admin/products-form";

const props = defineProps<{
    product: {
        id: number;
        category_id: number;
        brand_id: number | null;
        sku: string;
        name: string;
        slug: string;
        description: string | null;
        price: string; // soles
        currency: string;
        stock_on_hand: number;
        is_active: boolean;
        deleted_at?: string | null;
    };
    categories: OptionItem[];
    brands: OptionItem[];
}>();

const submit = (payload: ProductFormPayload) => {
    router.put(`/admin/products/${props.product.id}`, payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout :title="`Editar: ${props.product.name}`">
        <div class="space-y-6">
            <AdminPageHeader title="Editar producto" description="Actualiza los datos del producto.">
                <template #actions>
                    <Link href="/admin/products" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4 space-y-3">
                <div class="text-sm text-muted-foreground">
                    <span class="font-medium text-foreground">Slug:</span>
                    <span class="ml-2 font-mono">{{ props.product.slug }}</span>
                    <span class="ml-2 text-xs">(no cambia automáticamente)</span>
                </div>

                <ProductForm :categories="props.categories" :brands="props.brands" :initial="{
                    category_id: props.product.category_id,
                    brand_id: props.product.brand_id,
                    sku: props.product.sku,
                    name: props.product.name,
                    description: props.product.description,
                    price: props.product.price,
                    currency: props.product.currency,
                    stock_on_hand: props.product.stock_on_hand,
                    is_active: props.product.is_active,
                }" submit-label="Guardar cambios" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
