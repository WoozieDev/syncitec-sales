<script setup lang="ts">
const props = defineProps<{
    loading?: boolean;
    type?: "button" | "submit";
    variant?: "primary" | "outline" | "destructive";
}>();
</script>

<template>
    <button :type="props.type ?? 'button'" :disabled="props.loading"
        class="inline-flex h-10 items-center justify-center gap-2 rounded-md px-4 text-sm transition disabled:opacity-60"
        :class="[
            props.variant === 'outline'
                ? 'border hover:bg-accent'
                : props.variant === 'destructive'
                    ? 'bg-destructive text-destructive-foreground hover:bg-destructive/90'
                    : 'bg-primary text-primary-foreground hover:bg-primary/90',
            props.variant === 'outline' ? 'border' : '',
        ]">
        <span v-if="props.loading">Procesando...</span>
        <slot v-else />
    </button>
</template>
