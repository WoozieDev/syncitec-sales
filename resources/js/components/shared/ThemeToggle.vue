<script setup lang="ts">
import { Sun, Moon, Laptop } from "lucide-vue-next";
import { useAppearance } from "@/composables/useAppearance";

const { appearance, updateAppearance } = useAppearance();
</script>

<template>
    <div class="flex items-center gap-1 rounded-md border bg-background p-1">
        <!-- Light -->
        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md transition
             hover:bg-accent" :class="appearance === 'light' ? 'bg-accent text-accent-foreground' : ''"
            @click="updateAppearance('light')" aria-label="Modo claro">
            <Sun class="h-4 w-4" />
        </button>

        <!-- Dark -->
        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md transition
             hover:bg-accent" :class="appearance === 'dark' ? 'bg-accent text-accent-foreground' : ''"
            @click="updateAppearance('dark')" aria-label="Modo oscuro">
            <Moon class="h-4 w-4" />
        </button>

        <!-- System -->
        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md transition
             hover:bg-accent" :class="appearance === 'system' ? 'bg-accent text-accent-foreground' : ''"
            @click="updateAppearance('system')" aria-label="Modo sistema">
            <Laptop class="h-4 w-4" />
        </button>
    </div>
</template>
