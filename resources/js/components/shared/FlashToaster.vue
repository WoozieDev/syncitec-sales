<script setup lang="ts">
import { watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Toaster, toast } from "vue-sonner";

type Flash = {
    success?: string | null;
    error?: string | null;
    warning?: string | null;
    info?: string | null;
};

type PageProps = {
    flash?: Flash;
};

const page = usePage<PageProps>();

watch(
    () => page.props.flash,
    (flash) => {
        if (!flash) return;

        if (flash.success) toast.success(flash.success);
        if (flash.error) toast.error(flash.error);
        if (flash.warning) toast.warning(flash.warning);
        if (flash.info) toast.message(flash.info);
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <!-- Global toaster -->
    <Toaster rich-colors position="top-right" />
</template>
