<script setup lang="ts">
const props = defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="rounded-xl border bg-card shadow-sm">
        <div class="flex items-start justify-between gap-4 border-b p-4">
            <div class="min-w-0">
                <h3 class="text-sm font-semibold">{{ props.title }}</h3>
                <p
                    v-if="props.description"
                    class="mt-1 text-xs text-muted-foreground"
                >
                    {{ props.description }}
                </p>
            </div>
            <div>
                <slot name="actions" />
            </div>
        </div>

        <div class="p-4">
            <slot />
        </div>
    </div>
</template>
