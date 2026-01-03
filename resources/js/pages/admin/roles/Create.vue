<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import RoleForm from "@/pages/admin/roles/partials/RoleForm.vue";

type PermissionOption = { id: number; name: string };

const props = defineProps<{
    permissions: PermissionOption[];
}>();

const submit = (payload: { name: string; permissions: string[] }) => {
    router.post("/admin/roles", payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout title="Crear rol">
        <div class="space-y-6">
            <AdminPageHeader title="Crear rol" description="Crea un nuevo rol y asigna permisos.">
                <template #actions>
                    <Link href="/admin/roles" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <RoleForm :permissions="props.permissions" submit-label="Crear" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
