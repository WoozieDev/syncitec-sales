<script setup lang="ts">
import { reactive } from "vue";
import FormErrors from "@/components/shared/FormErrors.vue";
import LoadingButton from "@/components/shared/LoadingButton.vue";

const props = defineProps<{
    initial?: { name?: string; is_active?: boolean; sort_order?: number };
    submitLabel?: string;
    loading?: boolean;
}>();

const emit = defineEmits<{
    (e: "submit", payload: { name: string; is_active: boolean; sort_order: number }): void;
}>();

const form = reactive({
    name: props.initial?.name ?? "",
    is_active: props.initial?.is_active ?? true,
    sort_order: props.initial?.sort_order ?? 0,
});

const onSubmit = () => {
    emit("submit", {
        name: form.name,
        is_active: form.is_active,
        sort_order: Number(form.sort_order ?? 0),
    });
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="onSubmit">
        <FormErrors />

        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="text-sm font-medium">Nombre</label>
                <input v-model="form.name" class="h-10 w-full rounded-md border bg-background px-3 text-sm" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Orden</label>
                <input v-model.number="form.sort_order" type="number" min="0"
                    class="h-10 w-full rounded-md border bg-background px-3 text-sm" />
            </div>

            <div class="flex items-center gap-2 md:col-span-2">
                <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4" />
                <label for="is_active" class="text-sm">Activo</label>
            </div>
        </div>

        <div class="flex justify-end">
            <LoadingButton type="submit" :loading="props.loading" variant="primary">
                {{ props.submitLabel ?? "Guardar" }}
            </LoadingButton>
        </div>
    </form>
</template>
