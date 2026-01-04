<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import BrandForm from "@/pages/admin/brands/partials/BrandForm.vue";

const submit = (payload: { name: string; is_active: boolean; sort_order: number }) => {
    router.post("/admin/brands", payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout title="Crear marca">
        <div class="space-y-6">
            <AdminPageHeader title="Crear marca" description="Crea una nueva marca para productos.">
                <template #actions>
                    <Link href="/admin/brands" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">Volver</Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <BrandForm submit-label="Crear" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
