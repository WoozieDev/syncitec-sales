<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import BrandForm from "@/pages/admin/brands/partials/BrandForm.vue";

const props = defineProps<{
    brand: {
        id: number;
        name: string;
        slug: string;
        is_active: boolean;
        sort_order: number;
        deleted_at?: string | null;
    };
}>();

const submit = (payload: { name: string; is_active: boolean; sort_order: number }) => {
    router.put(`/admin/brands/${props.brand.id}`, payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout :title="`Editar: ${props.brand.name}`">
        <div class="space-y-6">
            <AdminPageHeader title="Editar marca" description="Actualiza información de la marca.">
                <template #actions>
                    <Link href="/admin/brands" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">Volver</Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4 space-y-3">
                <div class="text-sm text-muted-foreground">
                    <span class="font-medium text-foreground">Slug:</span>
                    <span class="ml-2 font-mono">{{ props.brand.slug }}</span>
                    <span class="ml-2 text-xs">(no cambia automáticamente)</span>
                </div>

                <BrandForm
                    :initial="{ name: props.brand.name, is_active: props.brand.is_active, sort_order: props.brand.sort_order }"
                    submit-label="Guardar cambios" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
