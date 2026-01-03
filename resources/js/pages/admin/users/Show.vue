<script setup lang="ts">
import { computed, ref } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import ConfirmDialog from "@/components/shared/ConfirmDialog.vue";

import { usePermissions } from "@/composables/usePermissions";

import type { UserDetail } from "@/types/admin/users";

const props = defineProps<{
    user: UserDetail & {
        deleted_at?: string | null;
    };
}>();

const isDeleted = computed(() => !!props.user.deleted_at);

const { can } = usePermissions();

const canUpdate = computed(() => can("users.update"));
const canDelete = computed(() => can("users.delete"));

const isProtected = computed(() => props.user.is_superadmin);


const confirmOpen = ref(false);

const onDelete = () => {
    confirmOpen.value = true;
};

const doDelete = () => {
    router.delete(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
    });
};

const doRestore = () => {
    router.put(`/admin/users/${props.user.id}/restore`, {}, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout :title="props.user ? `Usuario: ${props.user.name}` : 'Usuario'">
        <div class="space-y-6">
            <AdminPageHeader :title="props.user.name" description="Detalle del usuario y su información.">
                <template #actions>
                    <template v-if="isProtected">
                        <span class="text-sm text-muted-foreground">Usuario protegido</span>
                    </template>

                    <template v-else>
                        <Link v-if="canUpdate" :href="`/admin/users/${props.user.id}/edit`"
                            class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                            Editar
                        </Link>

                        <button v-if="!isDeleted && canDelete" type="button"
                            class="rounded-md border border-destructive/40 px-3 py-2 text-sm text-destructive hover:bg-destructive/10"
                            @click="onDelete">
                            Eliminar
                        </button>

                        <button v-if="isDeleted && canUpdate" type="button"
                            class="rounded-md border px-3 py-2 text-sm hover:bg-accent" @click="doRestore">
                            Restaurar
                        </button>
                    </template>

                    <Link href="/admin/users" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Volver
                    </Link>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-sm text-muted-foreground">Email</dt>
                        <dd class="font-medium">{{ props.user.email }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-muted-foreground">Rol</dt>
                        <dd class="font-medium">{{ props.user.roles?.[0] ?? "-" }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-muted-foreground">Estado</dt>
                        <dd class="font-medium">{{ props.user.is_active ? "Activo" : "Inactivo" }}</dd>
                    </div>

                    <div v-if="props.user.deleted_at">
                        <dt class="text-sm text-muted-foreground">Eliminado</dt>
                        <dd class="font-medium">{{ props.user.deleted_at }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border bg-card p-4">
                <h3 class="font-semibold">Compras del usuario</h3>
                <p class="mt-1 text-sm text-muted-foreground">
                    Aquí mostraremos pedidos/ventas asociadas cuando conectemos Orders y Sales.
                </p>
            </div>
        </div>

        <ConfirmDialog v-model:open="confirmOpen" title="Eliminar usuario"
            description="Esta acción enviará el usuario a la papelera (soft delete)." confirm-text="Sí, eliminar"
            cancel-text="Cancelar" :destructive="true" @confirm="doDelete" />
    </AdminLayout>
</template>
