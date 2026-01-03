<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import CategoryForm from "@/pages/admin/categories/partials/CategoryForm.vue";

import type { CategoryParent } from "@/types/admin/categories";

const props = defineProps<{
    parents: CategoryParent[];
}>();

const submit = (payload: {
    name: string;
    parent_id: number | null;
    is_active: boolean;
    sort_order: number;
}) => {
    router.post("/admin/categories", payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout title="Crear categoría">
        <div class="space-y-6">
            <AdminPageHeader title="Crear categoría" description="Crea una categoría o subcategoría.">
                <template #actions>
                    <Link href="/admin/categories" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <CategoryForm :parents="props.parents" submit-label="Crear" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
