<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import UserForm from "@/pages/admin/users/Partials/UserForm.vue";

import type { RoleOption } from "@/types/admin/users";

const props = defineProps<{
    roles: RoleOption[];
    user: {
        id: number;
        name: string;
        email: string;
        is_active: boolean;
        role: string | null;
    };
}>();

const submit = (payload: {
    name: string;
    email: string;
    password: string;
    is_active: boolean;
    role: string;
}) => {
    router.put(`/admin/users/${props.user.id}`, payload, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout :title="`Editar: ${props.user.name}`">
        <div class="space-y-6">
            <AdminPageHeader title="Editar usuario" description="Actualiza la información del usuario.">
                <template #actions>
                    <Link :href="`/admin/users/${props.user.id}`"
                        class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Ver
                    </Link>

                    <Link href="/admin/users" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <UserForm :roles="props.roles" :initial="props.user" submit-label="Guardar cambios" @submit="submit" />
                <p class="mt-3 text-xs text-muted-foreground">
                    Si no deseas cambiar la contraseña, deja ese campo vacío.
                </p>
            </div>
        </div>
    </AdminLayout>
</template>
