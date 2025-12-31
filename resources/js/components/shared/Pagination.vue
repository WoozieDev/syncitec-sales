<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

const props = defineProps<{
    links: PaginationLink[];
}>();

const visibleLinks = computed(() => {
    // Filtra links vacíos si vienen null
    return (props.links ?? []).filter((l) => l.label !== "...");
});

const hasLinks = computed(() => (props.links?.length ?? 0) > 0);
</script>

<template>
    <nav v-if="hasLinks" class="flex items-center justify-center gap-1">
        <template v-for="(link, idx) in visibleLinks" :key="idx">
            <span v-if="!link.url"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm text-muted-foreground opacity-60"
                v-html="link.label" />
            <Link v-else :href="link.url"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm transition hover:bg-accent"
                :class="link.active ? 'bg-accent text-accent-foreground' : ''" v-html="link.label" />
        </template>
    </nav>
</template>
