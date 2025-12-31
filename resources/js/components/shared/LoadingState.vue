<script setup lang="ts">
const props = defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="rounded-lg border bg-card p-8 text-center">
        <div class="mx-auto max-w-md space-y-2">
            <div class="text-base font-semibold">
                {{ props.title ?? "Cargando..." }}
            </div>
            <p class="text-sm text-muted-foreground">
                {{ props.description ?? "Por favor espera un momento." }}
            </p>
        </div>
    </div>
</template>
