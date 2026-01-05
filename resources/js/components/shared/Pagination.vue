<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

const props = defineProps<{
    links: PaginationLink[];
}>();

const hasLinks = computed(() => (props.links?.length ?? 0) > 0);

// Quitamos "..." para MVP (tu decisión original). Si luego quieres, lo reactivamos.
const visibleLinks = computed(() => {
    return (props.links ?? []).filter((l) => l.label !== '...');
});
</script>

<template>
    <nav
        v-if="hasLinks"
        class="flex flex-wrap items-center justify-center gap-1"
        aria-label="Pagination"
    >
        <template v-for="(link, idx) in visibleLinks" :key="idx">
            <!-- Disabled -->
            <span
                v-if="!link.url"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border bg-background px-3 text-sm text-muted-foreground opacity-60 select-none"
                v-html="link.label"
            />

            <!-- Active / Normal -->
            <Link
                v-else
                :href="link.url"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border bg-background px-3 text-sm transition hover:bg-accent hover:text-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                :class="
                    link.active
                        ? 'border-primary/30 bg-primary/10 font-medium text-foreground'
                        : ''
                "
                :aria-current="link.active ? 'page' : undefined"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
