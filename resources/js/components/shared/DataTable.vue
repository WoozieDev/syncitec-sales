<script setup lang="ts">
const props = defineProps<{
    striped?: boolean;
}>();
</script>

<template>
    <div class="overflow-hidden rounded-lg border bg-card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b bg-muted/40">
                    <tr>
                        <slot name="head" />
                    </tr>
                </thead>

                <tbody>
                    <slot name="body" />
                </tbody>
            </table>
        </div>

        <div v-if="$slots.footer" class="border-t bg-muted/20 p-3">
            <slot name="footer" />
        </div>
    </div>
</template>

<style scoped>
/* opcional: si luego quieres striped por prop */
</style>
