<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    title: string;
    value: string | number;
    hint?: string;
    delta?: number; // % change
    icon?: any;
}>();

const deltaText = computed(() => {
    if (props.delta === undefined || props.delta === null) return null;
    const sign = props.delta >= 0 ? '+' : '';
    return `${sign}${props.delta}%`;
});

const deltaClass = computed(() => {
    if (props.delta === undefined || props.delta === null) return '';
    return props.delta >= 0
        ? 'text-green-600 dark:text-green-400'
        : 'text-red-600 dark:text-red-400';
});
</script>

<template>
    <div class="rounded-xl border bg-card p-4 shadow-sm">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm text-muted-foreground">{{ title }}</p>
                <div class="mt-1 flex items-end gap-2">
                    <p class="text-2xl leading-none font-semibold">
                        {{ value }}
                    </p>
                    <p
                        v-if="deltaText"
                        class="text-sm font-medium"
                        :class="deltaClass"
                    >
                        {{ deltaText }}
                    </p>
                </div>
                <p v-if="hint" class="mt-2 text-xs text-muted-foreground">
                    {{ hint }}
                </p>
            </div>

            <div
                v-if="icon"
                class="flex h-10 w-10 items-center justify-center rounded-lg border bg-background"
            >
                <component :is="icon" class="h-5 w-5 text-muted-foreground" />
            </div>
        </div>
    </div>
</template>
