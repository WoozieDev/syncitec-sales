<script setup lang="ts">
import { computed, ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import FiltersBar from "@/components/shared/FiltersBar.vue";
import SearchInput from "@/components/shared/SearchInput.vue";
import DataTable from "@/components/shared/DataTable.vue";
import Pagination from "@/components/shared/Pagination.vue";
import EmptyState from "@/components/shared/EmptyState.vue";

import type { PaginationLink } from "@/types/pagination";
import type { RoleOption, UserFilters, UserListItem } from "@/types/admin/users";

const props = defineProps<{
    users: {
        data: UserListItem[];
        links: PaginationLink[];
    };
    roles: RoleOption[];
    filters: UserFilters;
}>();

// Local reactive filters (initialized from server)
const search = ref(props.filters.search ?? "");
const role = ref<string>(props.filters.role ?? "");
const status = ref<string>(props.filters.status ?? "");
const trashed = ref<string>(props.filters.trashed ?? "without");

const isFiltering = computed(() => {
    return (
        search.value.trim() !== "" ||
        role.value !== "" ||
        status.value !== "" ||
        trashed.value !== "without"
    );
});

const apply = () => {
    router.get(
        "/admin/users",
        {
            search: search.value.trim() || undefined,
            role: role.value || undefined,
            status: status.value || undefined,
            trashed: trashed.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const clear = () => {
    search.value = "";
    role.value = "";
    status.value = "";
    trashed.value = "without";
    apply();
};

// Optional: auto-apply on select changes (role/status/trashed)
watch([role, status, trashed], () => apply());
</script>

<template>
    <AdminLayout title="Usuarios">
        <div class="space-y-6">
            <AdminPageHeader title="Usuarios" description="Gestiona usuarios, roles y accesos al sistema.">
                <template #actions>
                    <Link href="/admin/users/create"
                        class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                        Nuevo usuario
                    </Link>
                </template>
            </AdminPageHeader>

            <FiltersBar>
                <template #left>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="w-full md:w-80">
                            <SearchInput v-model="search" placeholder="Buscar por nombre o email..." @submit="apply"
                                @clear="clear" />
                        </div>

                        <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
                            <!-- Role -->
                            <select v-model="role" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="">Rol: Todos</option>
                                <option v-for="r in props.roles" :key="r.id" :value="r.name">
                                    {{ r.name }}
                                </option>
                            </select>

                            <!-- Status -->
                            <select v-model="status" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="">Estado: Todos</option>
                                <option value="active">Activos</option>
                                <option value="inactive">Inactivos</option>
                            </select>

                            <!-- Trashed -->
                            <select v-model="trashed" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="without">Eliminados: No</option>
                                <option value="with">Eliminados: Incluir</option>
                                <option value="only">Eliminados: Solo</option>
                            </select>
                        </div>
                    </div>
                </template>

                <template #right>
                    <button v-if="isFiltering" type="button" class="h-10 rounded-md border px-3 text-sm hover:bg-accent"
                        @click="clear">
                        Limpiar
                    </button>

                    <button type="button" class="h-10 rounded-md border px-3 text-sm hover:bg-accent" @click="apply">
                        Aplicar
                    </button>
                </template>
            </FiltersBar>

            <template v-if="props.users.data.length > 0">
                <DataTable>
                    <template #head>
                        <th class="px-4 py-3 text-left font-medium">Nombre</th>
                        <th class="px-4 py-3 text-left font-medium">Email</th>
                        <th class="px-4 py-3 text-left font-medium">Rol</th>
                        <th class="px-4 py-3 text-left font-medium">Estado</th>
                        <th class="px-4 py-3 text-right font-medium">Acciones</th>
                    </template>

                    <template #body>
                        <tr v-for="u in props.users.data" :key="u.id" class="border-b last:border-0">
                            <td class="px-4 py-3 font-medium">
                                <span v-if="u.deleted_at" class="line-through opacity-70">
                                    {{ u.name }}
                                </span>
                                <span v-else>
                                    {{ u.name }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-muted-foreground">
                                {{ u.email }}
                            </td>

                            <td class="px-4 py-3 text-muted-foreground">
                                {{ u.roles?.[0] ?? "-" }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs"
                                    :class="u.is_active ? 'border-green-500/30 bg-green-500/10 text-green-600 dark:text-green-400' : 'border-amber-500/30 bg-amber-500/10 text-amber-600 dark:text-amber-400'">
                                    {{ u.is_active ? "Activo" : "Inactivo" }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <Link :href="`/admin/users/${u.id}`"
                                    class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                                    Ver
                                </Link>
                            </td>
                        </tr>
                    </template>

                    <template #footer>
                        <Pagination :links="props.users.links" />
                    </template>
                </DataTable>
            </template>

            <EmptyState v-else title="Sin usuarios" description="No se encontraron usuarios con los filtros actuales.">
                <template #actions>
                    <Link href="/admin/users/create"
                        class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                        Crear usuario
                    </Link>
                </template>
            </EmptyState>
        </div>
    </AdminLayout>
</template>
