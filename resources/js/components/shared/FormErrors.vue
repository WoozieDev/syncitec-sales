<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

type PageProps = {
    errors?: Record<string, string>;
};

const page = usePage<PageProps>();

const errors = computed(() => page.props.errors ?? {});
const list = computed(() => Object.values(errors.value));
const hasErrors = computed(() => list.value.length > 0);
</script>

<template>
    <div v-if="hasErrors" class="rounded-lg border border-destructive/30 bg-destructive/10 p-4" role="alert"
        aria-live="polite">
        <div class="text-sm font-semibold text-destructive">
            Revisa los campos del formulario
        </div>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-destructive">
            <li v-for="(msg, idx) in list" :key="idx">
                {{ msg }}
            </li>
        </ul>
    </div>
</template>
