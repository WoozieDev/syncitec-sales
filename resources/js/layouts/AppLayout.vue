<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import FlashToaster from "@/components/shared/FlashToaster.vue";

const props = defineProps<{
    title?: string;
}>();
</script>

<template>

    <Head :title="props.title ? `${props.title}` : 'App'" />

    <div class="min-h-screen bg-background text-foreground">
        <FlashToaster />
        <slot />
    </div>
</template>
