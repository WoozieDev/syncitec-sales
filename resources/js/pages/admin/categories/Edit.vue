<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import CategoryForm from "@/pages/admin/categories/partials/CategoryForm.vue";

import type { CategoryParent } from "@/types/admin/categories";

const props = defineProps<{
    category: {
        id: number;
        name: string;
        slug: string;
        parent_id: number | null;
        is_active: boolean;
        sort_order: number;
    };
    parents: CategoryParent[];
}>();

const submit = (payload: {
    name: string;
    parent_id: number | null;
    is_active: boolean;
    sort_order: number;
}) => {
    router.put(`/admin/categories/${props.category.id}`, payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout :title="`Editar: ${props.category.name}`">
        <div class="space-y-6">
            <AdminPageHeader title="Editar categoría" description="Actualiza la información de la categoría.">
                <template #actions>
                    <Link href="/admin/categories" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4 space-y-3">
                <div class="text-sm text-muted-foreground">
                    <span class="font-medium text-foreground">Slug:</span>
                    <span class="ml-2 font-mono">{{ props.category.slug }}</span>
                    <span class="ml-2 text-xs">(no cambia automáticamente)</span>
                </div>

                <CategoryForm :parents="props.parents" :initial="{
                    name: props.category.name,
                    parent_id: props.category.parent_id,
                    is_active: props.category.is_active,
                    sort_order: props.category.sort_order,
                }" submit-label="Guardar cambios" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
