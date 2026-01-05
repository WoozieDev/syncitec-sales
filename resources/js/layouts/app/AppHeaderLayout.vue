<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Moon, Store, Sun } from 'lucide-vue-next';
import { computed } from 'vue';

import { updateTheme } from '@/composables/useAppearance';

const page = usePage();

const appearance = computed(() => {
    // Si ya guardas el theme en cookie, aquí puedes leerlo desde props o cookie.
    // Por MVP, asumimos que el starter kit ya lo maneja.
    return 'system';
});

const toggleTheme = () => {
    const root = document.documentElement;
    const isDark = root.classList.contains('dark');
    updateTheme(isDark ? 'light' : 'dark');
};
</script>

<template>
    <header class="sticky top-0 z-30 border-b bg-background/80 backdrop-blur">
        <div class="flex h-14 items-center justify-between px-4 md:px-6">
            <!-- Left: breadcrumbs placeholder -->
            <div class="min-w-0">
                <div class="truncate text-sm font-medium">Panel</div>
            </div>

            <!-- Right: actions -->
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-md border hover:bg-accent"
                    title="Cambiar tema"
                    @click="toggleTheme"
                >
                    <Sun class="h-4 w-4 dark:hidden" />
                    <Moon class="hidden h-4 w-4 dark:block" />
                </button>

                <Link
                    href="/"
                    class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:bg-accent"
                >
                    <Store class="h-4 w-4" />
                    <span class="hidden sm:inline">Ver tienda</span>
                </Link>

                <!-- Aquí puedes montar tu UserMenuContent.vue -->
                <div class="ml-1">
                    <!-- Placeholder: reemplaza por tu componente real -->
                    <span class="text-sm text-muted-foreground">Cuenta</span>
                </div>
            </div>
        </div>
    </header>
</template>
