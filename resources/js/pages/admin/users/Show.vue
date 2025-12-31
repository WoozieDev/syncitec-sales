<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";

type UserDetail = {
    id: number;
    name: string;
    email: string;
    roles?: string[];
    created_at?: string;
};

const props = defineProps<{
    user?: UserDetail;
}>();
</script>

<template>
    <AdminLayout :title="props.user ? `Usuario: ${props.user.name}` : 'Usuario'">
        <div class="space-y-6">
            <AdminPageHeader :title="props.user?.name ?? 'Usuario'" description="Detalle del usuario y su información.">
                <template #actions>
                    <Link v-if="props.user" :href="`/admin/users/${props.user.id}/edit`"
                        class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Editar
                    </Link>

                    <Link href="/admin/users" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-sm text-muted-foreground">Email</dt>
                        <dd class="font-medium">{{ props.user?.email ?? "-" }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-muted-foreground">Rol</dt>
                        <dd class="font-medium">{{ props.user?.roles?.[0] ?? "-" }}</dd>
                    </div>
                </dl>
            </div>

            <!-- placeholder: compras/historial en el futuro -->
            <div class="rounded-lg border bg-card p-4">
                <h3 class="font-semibold">Compras del usuario</h3>
                <p class="mt-1 text-sm text-muted-foreground">
                    Aquí mostraremos pedidos/ventas asociadas cuando el módulo esté conectado.
                </p>
            </div>
        </div>
    </AdminLayout>
</template>
