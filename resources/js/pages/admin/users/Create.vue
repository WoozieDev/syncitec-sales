<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import UserForm from "@/pages/admin/users/Partials/UserForm.vue";

const submit = (payload: { name: string; email: string; is_active: boolean }) => {
    // In Fase 5: router.post(...)
    console.log("create user payload", payload);
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
                <UserForm submit-label="Crear" @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>
