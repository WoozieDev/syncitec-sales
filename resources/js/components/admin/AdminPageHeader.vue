<script setup lang="ts">
const props = defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ props.title }}
            </h1>
            <p v-if="props.description" class="text-sm text-muted-foreground">
                {{ props.description }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
