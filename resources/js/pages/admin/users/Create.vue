<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import UserForm from "@/pages/admin/users/Partials/UserForm.vue";

import type { RoleOption } from "@/types/admin/users";

const props = defineProps<{
    roles: RoleOption[];
}>();

const submit = (payload: {
    name: string;
    email: string;
    password: string;
    is_active: boolean;
    role: string;
}) => {
    router.post("/admin/users", payload, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout title="Crear usuario">
        <div class="space-y-6">
            <AdminPageHeader title="Crear usuario" description="Registra un nuevo usuario.">
                <template #actions>
                    <Link href="/admin/users" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <UserForm :roles="props.roles" submit-label="Crear" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
