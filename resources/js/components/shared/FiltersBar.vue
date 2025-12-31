<script setup lang="ts">
const props = defineProps<{
    compact?: boolean;
}>();
</script>

<template>
    <div class="flex flex-col gap-3 rounded-lg border bg-card p-4 md:flex-row md:items-center md:justify-between"
        :class="props.compact ? 'p-3' : ''">
        <div class="flex-1">
            <slot name="left" />
        </div>

        <div class="flex items-center gap-2">
            <slot name="right" />
        </div>
    </div>
</template>
