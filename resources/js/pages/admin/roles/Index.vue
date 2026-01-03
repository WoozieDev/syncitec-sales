<script setup lang="ts">
import { computed, ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { Pencil, Trash2 } from "lucide-vue-next";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import FiltersBar from "@/components/shared/FiltersBar.vue";
import SearchInput from "@/components/shared/SearchInput.vue";
import DataTable from "@/components/shared/DataTable.vue";
import Pagination from "@/components/shared/Pagination.vue";
import EmptyState from "@/components/shared/EmptyState.vue";
import ConfirmDialog from "@/components/shared/ConfirmDialog.vue";
import { usePermissions } from "@/composables/usePermissions";

import type { PaginationLink } from "@/types/pagination";

type RoleRow = {
    id: number;
    name: string;
    users_count: number;
    is_protected: boolean;
};

const props = defineProps<{
    roles: {
        data: RoleRow[];
        links: PaginationLink[];
    };
    filters: { search?: string };
}>();

const { can } = usePermissions();
const canUpdate = computed(() => can("roles.update"));
const canDelete = computed(() => can("roles.delete"));
const canCreate = computed(() => can("roles.create"));

const search = ref(props.filters.search ?? "");

const apply = () => {
    router.get(
        "/admin/roles",
        { search: search.value.trim() || undefined },
        { preserveScroll: true, preserveState: true, replace: true }
    );
};

const clear = () => {
    search.value = "";
    apply();
};

// delete confirm
const confirmOpen = ref(false);
const selectedRoleId = ref<number | null>(null);

const confirmDelete = (id: number) => {
    selectedRoleId.value = id;
    confirmOpen.value = true;
};

const doDelete = () => {
    if (!selectedRoleId.value) return;
    router.delete(`/admin/roles/${selectedRoleId.value}`, { preserveScroll: true });
    confirmOpen.value = false;
    selectedRoleId.value = null;
};
</script>

<template>
    <AdminLayout title="Roles">
        <div class="space-y-6">
            <AdminPageHeader title="Roles" description="Gestiona roles del sistema y sus accesos.">
                <template #actions>
                    <Link v-if="canCreate" href="/admin/roles/create"
                        class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                        Nuevo rol
                    </Link>
                </template>
            </AdminPageHeader>

            <FiltersBar>
                <template #left>
                    <div class="w-full md:w-80">
                        <SearchInput v-model="search" placeholder="Buscar rol..." @submit="apply" @clear="clear" />
                    </div>
                </template>

                <template #right>
                    <button v-if="search" type="button" class="h-10 rounded-md border px-3 text-sm hover:bg-accent"
                        @click="clear">
                        Limpiar
                    </button>

                    <button type="button" class="h-10 rounded-md border px-3 text-sm hover:bg-accent" @click="apply">
                        Aplicar
                    </button>
                </template>
            </FiltersBar>

            <template v-if="props.roles.data.length">
                <DataTable>
                    <template #head>
                        <th class="px-4 py-3 text-left font-medium">Nombre</th>
                        <th class="px-4 py-3 text-left font-medium">Usuarios</th>
                        <th class="px-4 py-3 text-right font-medium">Acciones</th>
                    </template>

                    <template #body>
                        <tr v-for="r in props.roles.data" :key="r.id" class="border-b last:border-0">
                            <td class="px-4 py-3 font-medium">
                                {{ r.name }}
                                <span v-if="r.is_protected" class="ml-2 text-xs text-muted-foreground">
                                    (Protegido)
                                </span>
                            </td>

                            <td class="px-4 py-3 text-muted-foreground">
                                {{ r.users_count }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                <template v-if="r.is_protected">
                                    <span class="text-xs text-muted-foreground">Protegido</span>
                                </template>

                                <template v-else>
                                    <div class="flex justify-end gap-2">
                                        <Link v-if="canUpdate" :href="`/admin/roles/${r.id}/edit`"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border hover:bg-accent"
                                            title="Editar">
                                            <Pencil class="h-4 w-4" />
                                        </Link>

                                        <button v-if="canDelete" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md
                             border border-destructive/40 text-destructive hover:bg-destructive/10" title="Eliminar"
                                            @click="confirmDelete(r.id)">
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>

                    <template #footer>
                        <Pagination :links="props.roles.links" />
                    </template>
                </DataTable>
            </template>

            <EmptyState v-else title="Sin roles" description="No se encontraron roles con los filtros actuales." />

            <ConfirmDialog v-model:open="confirmOpen" title="Eliminar rol"
                description="Esta acción eliminará el rol del sistema." confirm-text="Sí, eliminar"
                cancel-text="Cancelar" :destructive="true" @confirm="doDelete" />
        </div>
    </AdminLayout>
</template>
