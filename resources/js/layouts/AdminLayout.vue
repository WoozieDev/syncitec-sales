<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';

const props = defineProps<{
    title?: string;
}>();

const pageTitle = computed(() =>
    props.title ? `${props.title} · Panel` : 'Panel',
);
</script>

<template>
    <Head :title="pageTitle" />

    <div class="min-h-screen bg-background text-foreground">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <AppSidebarLayout />

            <!-- Main -->
            <div class="flex min-w-0 flex-1 flex-col">
                <AppHeaderLayout />

                <!-- Content -->
                <main class="flex-1">
                    <div
                        class="mx-auto w-full max-w-[1400px] px-4 py-6 md:px-6"
                    >
                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
