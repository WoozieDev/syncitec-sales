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

import type { ProductsPagination, ProductFilters, OptionItem } from "@/types/admin/products";

const props = defineProps<{
    products: ProductsPagination;
    categories: OptionItem[];
    brands: OptionItem[];
    filters: ProductFilters;
}>();

const { can } = usePermissions();
const canCreate = computed(() => can("products.create"));
const canUpdate = computed(() => can("products.update"));
const canDelete = computed(() => can("products.delete"));

const search = ref(props.filters.search ?? "");
const category = ref<string>(props.filters.category ? String(props.filters.category) : "");
const brand = ref<string>(props.filters.brand ? String(props.filters.brand) : "");
const status = ref<string>(props.filters.status ?? "");
const trashed = ref<string>(props.filters.trashed ?? "without");

const isFiltering = computed(() => {
    return (
        search.value.trim() !== "" ||
        category.value !== "" ||
        brand.value !== "" ||
        status.value !== "" ||
        trashed.value !== "without"
    );
});

const apply = () => {
    router.get(
        "/admin/products",
        {
            search: search.value.trim() || undefined,
            category: category.value || undefined,
            brand: brand.value || undefined,
            status: status.value || undefined,
            trashed: trashed.value || undefined,
        },
        { preserveScroll: true, preserveState: true, replace: true }
    );
};

const clear = () => {
    search.value = "";
    category.value = "";
    brand.value = "";
    status.value = "";
    trashed.value = "without";
    apply();
};

watch([category, brand, status, trashed], () => apply());

// delete confirm
const confirmOpen = ref(false);
const selectedId = ref<number | null>(null);

const confirmDelete = (id: number) => {
    selectedId.value = id;
    confirmOpen.value = true;
};

const doDelete = () => {
    if (!selectedId.value) return;
    router.delete(`/admin/products/${selectedId.value}`, { preserveScroll: true });
    confirmOpen.value = false;
    selectedId.value = null;
};

const doRestore = (id: number) => {
    router.put(`/admin/products/${id}/restore`, {}, { preserveScroll: true });
};

const formatMoneyPEN = (amountCents: number) => {
    const soles = amountCents / 100;
    return new Intl.NumberFormat("es-PE", { style: "currency", currency: "PEN" }).format(soles);
};
</script>

<template>
    <AdminLayout title="Productos">
        <div class="space-y-6">
            <AdminPageHeader title="Productos" description="Gestiona productos, precios e inventario base.">
                <template #actions>
                    <Link v-if="canCreate" href="/admin/products/create"
                        class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                        Nuevo producto
                    </Link>
                </template>
            </AdminPageHeader>

            <FiltersBar>
                <template #left>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="w-full md:w-80">
                            <SearchInput v-model="search" placeholder="Buscar por nombre, SKU o slug..." @submit="apply"
                                @clear="clear" />
                        </div>

                        <div class="grid grid-cols-1 gap-2 md:grid-cols-4">
                            <select v-model="category" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="">Categoría: Todas</option>
                                <option v-for="c in props.categories" :key="c.id" :value="String(c.id)">
                                    {{ c.name }}
                                </option>
                            </select>

                            <select v-model="brand" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="">Marca: Todas</option>
                                <option v-for="b in props.brands" :key="b.id" :value="String(b.id)">
                                    {{ b.name }}
                                </option>
                            </select>

                            <select v-model="status" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="">Estado: Todos</option>
                                <option value="active">Activos</option>
                                <option value="inactive">Inactivos</option>
                            </select>

                            <select v-model="trashed" class="h-10 rounded-md border bg-background px-3 text-sm">
                                <option value="without">Eliminados: No</option>
                                <option value="with">Eliminados: Incluir</option>
                                <option value="only">Solo eliminados</option>
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

            <template v-if="props.products.data.length">
                <DataTable>
                    <template #head>
                        <th class="px-4 py-3 text-left font-medium">Producto</th>
                        <th class="px-4 py-3 text-left font-medium">SKU</th>
                        <th class="px-4 py-3 text-left font-medium">Categoría</th>
                        <th class="px-4 py-3 text-left font-medium">Marca</th>
                        <th class="px-4 py-3 text-left font-medium">Precio</th>
                        <th class="px-4 py-3 text-left font-medium">Stock</th>
                        <th class="px-4 py-3 text-left font-medium">Estado</th>
                        <th class="px-4 py-3 text-right font-medium">Acciones</th>
                    </template>

                    <template #body>
                        <tr v-for="p in props.products.data" :key="p.id" class="border-b last:border-0">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 overflow-hidden rounded-md border bg-muted">
                                        <img v-if="p.primary_image?.path" :src="`/${p.primary_image.path}`"
                                            class="h-full w-full object-cover" alt="" />
                                    </div>

                                    <div class="min-w-0">
                                        <div class="font-medium">
                                            <span :class="p.deleted_at ? 'line-through opacity-70' : ''">{{ p.name
                                                }}</span>
                                            <span v-if="p.deleted_at"
                                                class="ml-2 text-xs text-muted-foreground">(Eliminado)</span>
                                        </div>
                                        <div class="text-xs text-muted-foreground truncate">/{{ p.slug }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-muted-foreground">{{ p.sku }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ p.category?.name ?? "—" }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ p.brand?.name ?? "—" }}</td>

                            <td class="px-4 py-3 font-medium">
                                {{ formatMoneyPEN(p.price_amount) }}
                            </td>

                            <td class="px-4 py-3 text-muted-foreground">
                                {{ p.stock_on_hand }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs" :class="p.is_active
                                    ? 'border-green-500/30 bg-green-500/10 text-green-600 dark:text-green-400'
                                    : 'border-amber-500/30 bg-amber-500/10 text-amber-600 dark:text-amber-400'">
                                    {{ p.is_active ? "Activo" : "Inactivo" }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link v-if="canUpdate" :href="`/admin/products/${p.id}/edit`"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border hover:bg-accent"
                                        title="Editar">
                                        <Pencil class="h-4 w-4" />
                                    </Link>

                                    <button v-if="p.deleted_at && canUpdate" type="button"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border hover:bg-accent"
                                        title="Restaurar" @click="doRestore(p.id)">
                                        <RotateCcw class="h-4 w-4" />
                                    </button>

                                    <button v-else-if="canDelete" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md
                           border border-destructive/40 text-destructive hover:bg-destructive/10" title="Eliminar"
                                        @click="confirmDelete(p.id)">
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template #footer>
                        <Pagination :links="props.products.links" />
                    </template>
                </DataTable>
            </template>

            <EmptyState v-else title="Sin productos"
                description="No se encontraron productos con los filtros actuales.">
                <template #actions>
                    <Link v-if="canCreate" href="/admin/products/create"
                        class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                        Crear producto
                    </Link>
                </template>
            </EmptyState>

            <ConfirmDialog v-model:open="confirmOpen" title="Eliminar producto"
                description="Esta acción eliminará el producto (soft delete)." confirm-text="Sí, eliminar"
                cancel-text="Cancelar" :destructive="true" @confirm="doDelete" />
        </div>
    </AdminLayout>
</template>
