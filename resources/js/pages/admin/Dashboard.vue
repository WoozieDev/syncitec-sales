<script setup lang="ts">
import AdminPageHeader from '@/components/admin/AdminPageHeader.vue';
import SectionCard from '@/components/admin/SectionCard.vue';
import StatCard from '@/components/admin/StatCard.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

import { Link } from '@inertiajs/vue3';
import {
    AlertTriangle,
    ArrowRight,
    CreditCard,
    Package,
    ShoppingBag,
    Users,
} from 'lucide-vue-next';

// MVP: valores placeholder (luego los conectamos al backend)
const stats = [
    {
        title: 'Ventas hoy',
        value: 'S/ 0.00',
        hint: 'Incluye POS + Web',
        delta: 0,
        icon: CreditCard,
    },
    {
        title: 'Órdenes (mes)',
        value: 0,
        hint: 'Pendientes + completadas',
        delta: 0,
        icon: ShoppingBag,
    },
    {
        title: 'Productos activos',
        value: 0,
        hint: 'Catálogo publicado',
        delta: 0,
        icon: Package,
    },
    {
        title: 'Clientes',
        value: 0,
        hint: 'Registrados + guest',
        delta: 0,
        icon: Users,
    },
];
</script>

<template>
    <AdminLayout title="Dashboard">
        <div class="space-y-6">
            <AdminPageHeader
                title="Dashboard"
                description="Resumen rápido del negocio. (MVP: datos placeholder por ahora)."
            >
                <template #actions>
                    <div class="flex items-center gap-2">
                        <Link
                            href="/admin/products"
                            class="rounded-md border px-3 py-2 text-sm hover:bg-accent"
                        >
                            Ir a productos
                        </Link>
                        <Link
                            href="/admin/pos"
                            class="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground hover:bg-primary/90"
                        >
                            Abrir POS
                        </Link>
                    </div>
                </template>
            </AdminPageHeader>

            <!-- KPI cards -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <StatCard
                    v-for="s in stats"
                    :key="s.title"
                    :title="s.title"
                    :value="s.value"
                    :hint="s.hint"
                    :delta="s.delta"
                    :icon="s.icon"
                />
            </div>

            <!-- Main grid -->
            <div class="grid gap-4 lg:grid-cols-3">
                <!-- Left (2 cols) -->
                <div class="space-y-4 lg:col-span-2">
                    <!-- Orders summary -->
                    <SectionCard
                        title="Órdenes recientes"
                        description="Últimos movimientos (placeholder)."
                    >
                        <template #actions>
                            <Link
                                href="/admin/orders"
                                class="inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground"
                            >
                                Ver todo <ArrowRight class="h-4 w-4" />
                            </Link>
                        </template>

                        <div class="space-y-3">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="flex items-center justify-between rounded-lg border bg-background p-3"
                            >
                                <div class="min-w-0">
                                    <p class="text-sm font-medium">
                                        Orden #000{{ i }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Cliente: Demo · Estado: Pendiente
                                    </p>
                                </div>
                                <div class="text-sm font-semibold">S/ 0.00</div>
                            </div>

                            <p class="text-xs text-muted-foreground">
                                Nota: luego conectamos esto con `orders` y
                                estados reales.
                            </p>
                        </div>
                    </SectionCard>

                    <!-- Inventory / low stock -->
                    <SectionCard
                        title="Stock y alertas"
                        description="Productos con stock bajo (placeholder)."
                    >
                        <div class="space-y-3">
                            <div
                                class="flex items-start gap-3 rounded-lg border bg-background p-3"
                            >
                                <div
                                    class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-lg border bg-card"
                                >
                                    <AlertTriangle
                                        class="h-5 w-5 text-amber-600 dark:text-amber-400"
                                    />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium">
                                        Sin alertas por ahora
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Cuando activemos “low_stock_threshold”,
                                        se mostrará aquí.
                                    </p>
                                </div>
                            </div>

                            <!-- Simple bar placeholder -->
                            <div class="rounded-lg border bg-background p-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium">
                                        Nivel de inventario (demo)
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Últimos 7 días
                                    </p>
                                </div>
                                <div class="mt-3 grid grid-cols-7 gap-2">
                                    <div
                                        v-for="d in 7"
                                        :key="d"
                                        class="h-16 rounded-md bg-muted"
                                    />
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    Placeholder (luego lo conectamos a
                                    movimientos).
                                </p>
                            </div>
                        </div>
                    </SectionCard>
                </div>

                <!-- Right column -->
                <div class="space-y-4">
                    <SectionCard
                        title="Acciones rápidas"
                        description="Atajos para tareas comunes."
                    >
                        <div class="grid gap-2">
                            <Link
                                href="/admin/products/create"
                                class="rounded-lg border bg-background px-3 py-2 text-sm hover:bg-accent"
                            >
                                + Nuevo producto
                            </Link>
                            <Link
                                href="/admin/categories"
                                class="rounded-lg border bg-background px-3 py-2 text-sm hover:bg-accent"
                            >
                                Gestionar categorías
                            </Link>
                            <Link
                                href="/admin/brands"
                                class="rounded-lg border bg-background px-3 py-2 text-sm hover:bg-accent"
                            >
                                Gestionar marcas
                            </Link>
                            <Link
                                href="/admin/users"
                                class="rounded-lg border bg-background px-3 py-2 text-sm hover:bg-accent"
                            >
                                Administrar usuarios
                            </Link>
                        </div>
                    </SectionCard>

                    <SectionCard
                        title="Resumen pagos"
                        description="Mercado Pago / Yape / Transferencia (placeholder)."
                    >
                        <div class="space-y-3">
                            <div class="rounded-lg border bg-background p-3">
                                <p class="text-sm font-medium">
                                    Pagos confirmados
                                </p>
                                <p class="mt-1 text-2xl font-semibold">
                                    S/ 0.00
                                </p>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    Conectaremos esto cuando implementemos
                                    Payments.
                                </p>
                            </div>

                            <div class="rounded-lg border bg-background p-3">
                                <p class="text-sm font-medium">
                                    Pendientes por validar
                                </p>
                                <p class="mt-1 text-2xl font-semibold">0</p>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    Aquí caerán Yape/transfer con screenshot.
                                </p>
                            </div>
                        </div>
                    </SectionCard>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
