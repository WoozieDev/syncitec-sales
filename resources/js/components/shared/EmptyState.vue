<script setup lang="ts">
const props = defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="rounded-lg border bg-card p-8 text-center">
        <div class="mx-auto max-w-md space-y-2">
            <h3 class="text-base font-semibold">
                {{ props.title ?? "Sin resultados" }}
            </h3>
            <p class="text-sm text-muted-foreground">
                {{ props.description ?? "No hay registros para mostrar." }}
            </p>

            <div v-if="$slots.actions" class="mt-4 flex justify-center">
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>
