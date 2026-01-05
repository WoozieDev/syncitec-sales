<script setup lang="ts">
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';

defineProps<{
    text: string;
}>();
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <slot />
            </TooltipTrigger>
            <TooltipContent>
                <p class="text-xs">{{ text }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
