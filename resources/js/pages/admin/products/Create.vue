<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import ProductForm from "@/pages/admin/products/partials/ProductForm.vue";

import type { OptionItem } from "@/types/admin/products";
import type { ProductFormPayload } from "@/types/admin/products-form";

const props = defineProps<{
    categories: OptionItem[];
    brands: OptionItem[];
    defaults: {
        currency: string;
        is_active: boolean;
        stock_on_hand: number;
    };
}>();

const submit = (payload: ProductFormPayload) => {
    router.post("/admin/products", payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout title="Crear producto">
        <div class="space-y-6">
            <AdminPageHeader title="Crear producto" description="Crea un producto para POS y tienda.">
                <template #actions>
                    <Link href="/admin/products" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <ProductForm :categories="props.categories" :brands="props.brands" :initial="{
                    currency: props.defaults.currency,
                    is_active: props.defaults.is_active,
                    stock_on_hand: props.defaults.stock_on_hand,
                }" submit-label="Crear" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
