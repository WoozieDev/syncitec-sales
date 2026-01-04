<script setup lang="ts">
import { computed, ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { Pencil, Trash2, RotateCcw } from "lucide-vue-next";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import FiltersBar from "@/components/shared/FiltersBar.vue";
import SearchInput from "@/components/shared/SearchInput.vue";
import DataTable from "@/components/shared/DataTable.vue";
import Pagination from "@/components/shared/Pagination.vue";
import EmptyState from "@/components/shared/EmptyState.vue";
import ConfirmDialog from "@/components/shared/ConfirmDialog.vue";
import { usePermissions } from "@/composables/usePermissions";

import type { BrandsPagination, BrandFilters } from "@/types/admin/brands";

const props = defineProps<{
    brands: BrandsPagination;
    filters: BrandFilters;
}>();

const { can } = usePermissions();
const canCreate = computed(() => can("brands.create"));
const canUpdate = computed(() => can("brands.update"));
const canDelete = computed(() => can("brands.delete"));

const search = ref(props.filters.search ?? "");
const status = ref(props.filters.status ?? "");

const isFiltering = computed(() => search.value.trim() !== "" || status.value !== "");

const apply = () => {
    router.get(
        "/admin/brands",
        {
            search: search.value.trim() || undefined,
            status: status.value || undefined,
        },
        { preserveScroll: true, preserveState: true, replace: true }
    );
};

const clear = () => {
    search.value = "";
    status.value = "";
    apply();
};

watch([status], () => apply());

// delete confirm
const confirmOpen = ref(false);
const selectedId = ref<number | null>(null);

const confirmDelete = (id: number) => {
    selectedId.value = id;
    confirmOpen.value = true;
};

const doDelete = () => {
    if (!selectedId.value) return;
    router.delete(`/admin/brands/${selectedId.value}`, { preserveScroll: true });
    confirmOpen.value = false;
    selectedId.value = null;
};

const doRestore = (id: number) => {
    router.put(`/admin/brands/${id}/restore`, {}, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout title="Marcas">
        <div class="space-y-6">
            <AdminPageHeader title="Marcas" description="Gestiona marcas para productos del POS y tienda.">
                <template #actions>
                    <Link v-if="canCreate" href="/admin/brands/create"
                        class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                        Nueva marca
                    </Link>
                </template>
            </AdminPageHeader>

            <FiltersBar>
                <template #left>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="w-full md:w-80">
                            <SearchInput v-model="search" placeholder="Buscar por nombre o slug..." @submit="apply"
                                @clear="clear" />
                        </div>

                        <div class="grid grid-cols-1 gap-2 md:grid-cols-1">
                            <select v-model="status" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="">Estado: Todos</option>
                                <option value="active">Activos</option>
                                <option value="inactive">Inactivos</option>
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

            <template v-if="props.brands.data.length">
                <DataTable>
                    <template #head>
                        <th class="px-4 py-3 text-left font-medium">Nombre</th>
                        <th class="px-4 py-3 text-left font-medium">Slug</th>
                        <th class="px-4 py-3 text-left font-medium">Orden</th>
                        <th class="px-4 py-3 text-left font-medium">Estado</th>
                        <th class="px-4 py-3 text-right font-medium">Acciones</th>
                    </template>

                    <template #body>
                        <tr v-for="b in props.brands.data" :key="b.id" class="border-b last:border-0">
                            <td class="px-4 py-3 font-medium">
                                <span :class="b.deleted_at ? 'line-through opacity-70' : ''">{{ b.name }}</span>
                                <span v-if="b.deleted_at" class="ml-2 text-xs text-muted-foreground">(Eliminado)</span>
                            </td>

                            <td class="px-4 py-3 text-muted-foreground">{{ b.slug }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ b.sort_order }}</td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs" :class="b.is_active
                                    ? 'border-green-500/30 bg-green-500/10 text-green-600 dark:text-green-400'
                                    : 'border-amber-500/30 bg-amber-500/10 text-amber-600 dark:text-amber-400'">
                                    {{ b.is_active ? "Activo" : "Inactivo" }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link v-if="canUpdate" :href="`/admin/brands/${b.id}/edit`"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border hover:bg-accent"
                                        title="Editar">
                                        <Pencil class="h-4 w-4" />
                                    </Link>

                                    <button v-if="b.deleted_at && canUpdate" type="button"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border hover:bg-accent"
                                        title="Restaurar" @click="doRestore(b.id)">
                                        <RotateCcw class="h-4 w-4" />
                                    </button>

                                    <button v-else-if="canDelete" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md
                           border border-destructive/40 text-destructive hover:bg-destructive/10" title="Eliminar"
                                        @click="confirmDelete(b.id)">
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template #footer>
                        <Pagination :links="props.brands.links" />
                    </template>
                </DataTable>
            </template>

            <EmptyState v-else title="Sin marcas" description="No se encontraron marcas con los filtros actuales.">
                <template #actions>
                    <Link v-if="canCreate" href="/admin/brands/create"
                        class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                        Crear marca
                    </Link>
                </template>
            </EmptyState>

            <ConfirmDialog v-model:open="confirmOpen" title="Eliminar marca"
                description="Esta acción eliminará la marca (soft delete)." confirm-text="Sí, eliminar"
                cancel-text="Cancelar" :destructive="true" @confirm="doDelete" />
        </div>
    </AdminLayout>
</template>
