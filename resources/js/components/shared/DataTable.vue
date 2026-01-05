<script setup lang="ts">
const props = defineProps<{
    striped?: boolean;
}>();
</script>

<template>
    <div class="relative overflow-hidden rounded-lg border bg-card">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <!-- Header -->
                <thead class="border-b bg-muted/40">
                    <tr
                        class="text-left text-xs font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        <slot name="head" />
                    </tr>
                </thead>

                <!-- Body -->
                <tbody
                    class="divide-y divide-border"
                    :class="
                        props.striped
                            ? '[&>tr:nth-child(even)]:bg-muted/30'
                            : ''
                    "
                >
                    <slot name="body" />
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div v-if="$slots.footer" class="border-t bg-muted/20 px-4 py-3">
            <slot name="footer" />
        </div>
    </div>
</template>
