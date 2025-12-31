<script setup lang="ts">
import { computed, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { Menu } from "lucide-vue-next";

import AppLayout from "@/layouts/AppLayout.vue";
import ThemeToggle from "@/components/shared/ThemeToggle.vue";
import { adminNav } from "@/navigation/admin";
import { usePermissions } from "@/composables/usePermissions";

const props = defineProps<{
    title?: string;
}>();

/*
|--------------------------------------------------------------------------
| Auth / Permissions
|--------------------------------------------------------------------------
*/
type PageProps = {
    auth?: {
        user?: {
            name: string;
            email: string;
        } | null;
    };
};

const page = usePage<PageProps>();
const userName = computed(() => page.props.auth?.user?.name ?? "Usuario");

const { isAuthenticated, hasAnyRole, canAny } = usePermissions();

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/
const navItems = computed(() => {
    if (!isAuthenticated.value) {
        return adminNav.filter((item) => item.href === "/admin");
    }

    return adminNav.filter((item) => {
        const roleOk = !item.rolesAny || hasAnyRole(item.rolesAny);
        const permOk = !item.permissionsAny || canAny(item.permissionsAny);
        return roleOk && permOk;
    });
});

/*
|--------------------------------------------------------------------------
| Mobile sidebar state
|--------------------------------------------------------------------------
*/
const mobileOpen = ref(false);
const toggleMobile = () => (mobileOpen.value = !mobileOpen.value);
const closeMobile = () => (mobileOpen.value = false);

</script>

<template>
    <AppLayout :title="props.title">
        <div class="flex min-h-dvh">
            <!-- Sidebar (desktop) -->
            <aside class="hidden w-72 shrink-0 border-r bg-card/30 md:block">
                <div class="p-4">
                    <div class="text-lg font-semibold">Panel</div>
                    <div class="text-sm text-muted-foreground">POS + Ecommerce</div>
                </div>

                <nav class="px-2 pb-4 space-y-1">
                    <Link v-for="item in navItems" :key="item.href" :href="item.href" class="block rounded-md px-3 py-2 text-sm transition
                   hover:bg-accent hover:text-accent-foreground">
                        {{ item.label }}
                    </Link>
                </nav>
            </aside>

            <!-- Mobile overlay -->
            <div v-if="mobileOpen" class="fixed inset-0 z-40 bg-black/50 md:hidden" @click="closeMobile" />

            <!-- Sidebar (mobile) -->
            <aside v-if="mobileOpen" class="fixed left-0 top-0 z-50 h-dvh w-72 border-r bg-background md:hidden">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="text-lg font-semibold">Panel</div>
                        <div class="text-sm text-muted-foreground">POS + Ecommerce</div>
                    </div>
                    <button class="rounded-md border px-2 py-1 text-sm hover:bg-accent" @click="closeMobile">
                        Cerrar
                    </button>
                </div>

                <nav class="px-2 pb-4 space-y-1">
                    <Link v-for="item in navItems" :key="item.href" :href="item.href" class="block rounded-md px-3 py-2 text-sm transition
                   hover:bg-accent hover:text-accent-foreground" @click="closeMobile">
                        {{ item.label }}
                    </Link>
                </nav>
            </aside>

            <!-- Main content -->
            <div class="flex min-w-0 flex-1 flex-col">
                <!-- Topbar -->
                <header class="flex items-center gap-3 border-b bg-card/30 px-4 py-3">
                    <button class="inline-flex items-center justify-center rounded-md border p-2
                   hover:bg-accent md:hidden" @click="toggleMobile" aria-label="Abrir menú">
                        <Menu class="h-4 w-4" />
                    </button>

                    <div class="min-w-0 flex-1">
                        <div class="truncate text-base font-semibold">
                            {{ props.title ?? "Admin" }}
                        </div>
                        <div class="truncate text-sm text-muted-foreground">
                            {{ userName }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <ThemeToggle />
                        
                        <Link href="/" class="rounded-md border px-3 py-2 text-sm hover:bg-accent">
                            Ver tienda
                        </Link>
                    </div>
                </header>

                <!-- Page content -->
                <main class="flex-1 p-4 md:p-6">
                    <slot />
                </main>
            </div>
        </div>
    </AppLayout>
</template>
