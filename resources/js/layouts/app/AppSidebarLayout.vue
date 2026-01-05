<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed } from 'vue';

import { usePermissions } from '@/composables/usePermissions';
import { adminNav } from '@/navigation/admin';

type NavItem = {
    label: string;
    href: string;
    icon?: any;
    permissionsAny?: string[];
};

const page = usePage();
const { canAny } = usePermissions();

const sidebarOpen = computed<boolean>(() => {
    // viene desde Inertia share() como `sidebarOpen`
    return Boolean((page.props as any).sidebarOpen ?? true);
});

const setSidebarCookie = (value: boolean) => {
    // mismo nombre que ya usas en HandleInertiaRequests
    document.cookie = `sidebar_state=${value ? 'true' : 'false'};path=/;max-age=${60 * 60 * 24 * 365};SameSite=Lax`;
};

const toggleSidebar = () => {
    setSidebarCookie(!sidebarOpen.value);
    // Recargamos props sin recargar página completa
    window.location.reload();
};

const visibleItems = computed(() => {
    return (adminNav as NavItem[]).filter((item) => {
        if (!item.permissionsAny?.length) return true;
        return canAny(item.permissionsAny);
    });
});

const isActive = (href: string) => {
    const current = page.url.split('?')[0].split('#')[0];

    if (href === '/admin') {
        return current === href || current === `${href}/`;
    }
    return current === href || current.startsWith(`${href}/`);
};
</script>

<template>
    <aside
        class="sticky top-0 h-screen border-r bg-card"
        :class="sidebarOpen ? 'w-64' : 'w-[68px]'"
    >
        <div class="flex h-full flex-col">
            <!-- Brand -->
            <div class="flex h-14 items-center justify-between px-3">
                <div class="flex items-center gap-2 overflow-hidden">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-md bg-primary text-primary-foreground"
                    >
                        <span class="text-sm font-semibold">P</span>
                    </div>
                    <div v-if="sidebarOpen" class="leading-tight">
                        <div class="text-sm font-semibold">Panel</div>
                        <div class="text-xs text-muted-foreground">
                            POS + Ecommerce
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-md border hover:bg-accent"
                    @click="toggleSidebar"
                    :title="sidebarOpen ? 'Colapsar' : 'Expandir'"
                >
                    <ChevronLeft v-if="sidebarOpen" class="h-4 w-4" />
                    <ChevronRight v-else class="h-4 w-4" />
                </button>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto px-2 py-3">
                <div class="space-y-1">
                    <template v-for="item in visibleItems" :key="item.href">
                        <Link
                            :href="item.href"
                            class="group flex items-center rounded-md px-3 py-2 text-sm transition"
                            :class="[
                                isActive(item.href)
                                    ? 'bg-accent text-foreground'
                                    : 'text-muted-foreground hover:bg-accent hover:text-foreground',
                                sidebarOpen ? 'gap-3' : 'justify-center',
                            ]"
                            :title="!sidebarOpen ? item.label : undefined"
                        >
                            <component
                                :is="item.icon"
                                class="h-4 w-4 shrink-0"
                            />
                            <span v-if="sidebarOpen" class="truncate">{{
                                item.label
                            }}</span>
                        </Link>
                    </template>
                </div>
            </nav>

            <!-- Footer (opcional) -->
            <div class="border-t p-2">
                <div
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-xs text-muted-foreground"
                    :class="sidebarOpen ? '' : 'justify-center'"
                >
                    <span v-if="sidebarOpen">v1 MVP</span>
                    <span v-else>v1</span>
                </div>
            </div>
        </div>
    </aside>
</template>
