<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import UserForm from "@/pages/admin/users/Partials/UserForm.vue";

const props = defineProps<{
    user?: {
        id: number;
        name: string;
        email: string;
        is_active?: boolean;
    };
}>();

const submit = (payload: { name: string; email: string; is_active: boolean }) => {
    // In Fase 5: router.put(...)
    console.log("edit user payload", props.user?.id, payload);
};
</script>

<template>
    <AdminLayout :title="props.user ? `Editar: ${props.user.name}` : 'Editar usuario'">
        <div class="space-y-6">
            <AdminPageHeader title="Editar usuario" description="Actualiza la información del usuario.">
                <template #actions>
                    <Link href="/admin/users" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <UserForm :initial="props.user" submit-label="Guardar cambios" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
