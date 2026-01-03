<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import FiltersBar from "@/components/shared/FiltersBar.vue";
import SearchInput from "@/components/shared/SearchInput.vue";
import DataTable from "@/components/shared/DataTable.vue";
import Pagination from "@/components/shared/Pagination.vue";
import EmptyState from "@/components/shared/EmptyState.vue";

import type { PaginationLink } from "@/types/pagination";

type PermissionRow = {
    id: number;
    name: string;
    module: string;
};

const props = defineProps<{
    permissions: {
        data: PermissionRow[];
        links: PaginationLink[];
    };
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? "");

const apply = () => {
    router.get(
        "/admin/permissions",
        { search: search.value.trim() || undefined },
        { preserveScroll: true, preserveState: true, replace: true }
    );
};

const clear = () => {
    search.value = "";
    apply();
};
</script>

<template>
    <AdminLayout title="Permisos">
        <div class="space-y-6">
            <AdminPageHeader title="Permisos" description="Lista de permisos del sistema (solo lectura)." />

            <FiltersBar>
                <template #left>
                    <div class="w-full md:w-96">
                        <SearchInput v-model="search" placeholder="Buscar permiso..." @submit="apply" @clear="clear" />
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

            <template v-if="props.permissions.data.length">
                <DataTable>
                    <template #head>
                        <th class="px-4 py-3 text-left font-medium">Permiso</th>
                        <th class="px-4 py-3 text-left font-medium">Módulo</th>
                    </template>

                    <template #body>
                        <tr v-for="p in props.permissions.data" :key="p.id" class="border-b last:border-0">
                            <td class="px-4 py-3 font-medium">{{ p.name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ p.module }}</td>
                        </tr>
                    </template>

                    <template #footer>
                        <Pagination :links="props.permissions.links" />
                    </template>
                </DataTable>
            </template>

            <EmptyState v-else title="Sin permisos"
                description="No se encontraron permisos con los filtros actuales." />
        </div>
    </AdminLayout>
</template>
