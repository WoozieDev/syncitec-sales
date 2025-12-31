<script setup lang="ts">
import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminPageHeader from "@/components/admin/AdminPageHeader.vue";
import DataTable from "@/components/shared/DataTable.vue";

</script>

<template>
    <AdminLayout title="Dashboard">
        <div class="space-y-6">
            <AdminPageHeader title="Dashboard" description="Resumen de ventas, pedidos e inventario.">
                <template #actions>
                    <a href="/" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                        Ver tienda
                    </a>
                </template>
            </AdminPageHeader>

            <div class="rounded-lg border bg-card p-4">
                <p class="text-sm text-muted-foreground">
                    Aquí irán los KPIs y tablas recientes.
                </p>
            </div>

            <DataTable>
                <template #head>
                    <th class="px-4 py-3 text-left font-medium">Columna</th>
                    <th class="px-4 py-3 text-left font-medium">Valor</th>
                </template>

                <template #body>
                    <tr class="border-b last:border-0">
                        <td class="px-4 py-3">Ejemplo</td>
                        <td class="px-4 py-3 text-muted-foreground">Fila demo</td>
                    </tr>
                </template>
            </DataTable>
        </div>
    </AdminLayout>
</template>
