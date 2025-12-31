<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import FiltersBar from "@/components/shared/FiltersBar.vue";
import SearchInput from "@/components/shared/SearchInput.vue";
import DataTable from "@/components/shared/DataTable.vue";
import Pagination from "@/components/shared/Pagination.vue";
import EmptyState from "@/components/shared/EmptyState.vue";

// Placeholder props shape (in Fase 5 this will come from backend)
type UserRow = {
    id: number;
    name: string;
    email: string;
    roles?: string[];
};

type PaginationLink = { url: string | null; label: string; active: boolean };

const props = defineProps<{
    users?: {
        data: UserRow[];
        links: PaginationLink[];
    };
    filters?: {
        search?: string;
    };
}>();

const search = ref(props.filters?.search ?? "");

// In Fase 5: this will call router.get with preserveState
const apply = () => {
    // placeholder
};

const clear = () => {
    search.value = "";
    // placeholder
};
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
                    <SearchInput v-model="search" placeholder="Buscar por nombre o email..." @submit="apply"
                        @clear="clear" />
                </template>

                <template #right>
                    <!-- aquí irán filtros extra (rol, estado, etc.) -->
                </template>
            </FiltersBar>

            <template v-if="(props.users?.data?.length ?? 0) > 0">
                <DataTable>
                    <template #head>
                        <th class="px-4 py-3 text-left font-medium">Nombre</th>
                        <th class="px-4 py-3 text-left font-medium">Email</th>
                        <th class="px-4 py-3 text-left font-medium">Rol</th>
                        <th class="px-4 py-3 text-right font-medium">Acciones</th>
                    </template>

                    <template #body>
                        <tr v-for="u in props.users!.data" :key="u.id" class="border-b last:border-0">
                            <td class="px-4 py-3 font-medium">{{ u.name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ u.email }}</td>
                            <td class="px-4 py-3 text-muted-foreground">
                                {{ (u.roles?.[0] ?? "-") }}
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
                        <Pagination :links="props.users!.links" />
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
