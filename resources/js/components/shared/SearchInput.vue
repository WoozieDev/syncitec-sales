<script setup lang="ts">
import { computed } from "vue";
import { Search } from "lucide-vue-next";

const props = defineProps<{
    modelValue: string;
    placeholder?: string;
}>();

const emit = defineEmits<{
    (e: "update:modelValue", value: string): void;
    (e: "submit"): void;
    (e: "clear"): void;
}>();

const value = computed({
    get: () => props.modelValue,
    set: (v: string) => emit("update:modelValue", v),
});

const onKeydown = (e: KeyboardEvent) => {
    if (e.key === "Enter") emit("submit");
    if (e.key === "Escape") emit("clear");
};
</script>

<template>
    <div class="relative w-full">
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <input v-model="value" :placeholder="placeholder ?? 'Buscar...'" class="h-10 w-full rounded-md border bg-background pl-9 pr-3 text-sm outline-none
             focus-visible:ring-2 focus-visible:ring-ring" @keydown="onKeydown" />
    </div>
</template>
