<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    label: string;
    variant?: 'default' | 'destructive';
    disabled?: boolean;
}>();

const classes = computed(() => {
    const base =
        'inline-flex h-8 w-8 items-center justify-center rounded-md border transition ' +
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring';

    if (props.disabled) {
        return base + ' opacity-50 pointer-events-none';
    }

    if (props.variant === 'destructive') {
        return (
            base +
            ' border-destructive/40 text-destructive hover:bg-destructive/10'
        );
    }

    return base + ' hover:bg-accent';
});
</script>

<template>
    <button type="button" :class="classes" :disabled="props.disabled" class="cursor-pointer">
        <slot />
        <span class="sr-only">{{ props.label }}</span>
    </button>
</template>
