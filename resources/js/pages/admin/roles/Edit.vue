<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import RoleForm from "@/pages/admin/roles/partials/RoleForm.vue";

type PermissionOption = { id: number; name: string };

const props = defineProps<{
    role: {
        id: number;
        name: string;
        permissions: string[];
        is_protected: boolean;
    };
    permissions: PermissionOption[];
}>();

const submit = (payload: { name: string; permissions: string[] }) => {
    router.put(`/admin/roles/${props.role.id}`, payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout :title="`Editar rol: ${props.role.name}`">
        <div class="space-y-6">
            <AdminPageHeader title="Editar rol" description="Actualiza nombre y permisos del rol.">
                <template #actions>
                    <Link href="/admin/roles" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <RoleForm :permissions="props.permissions"
                    :initial="{ name: props.role.name, permissions: props.role.permissions }"
                    submit-label="Guardar cambios" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
