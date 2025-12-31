<script setup lang="ts">
import { reactive } from "vue";

const props = defineProps<{
    initial?: {
        name?: string;
        email?: string;
        is_active?: boolean;
    };
    submitLabel?: string;
}>();

const emit = defineEmits<{
    (e: "submit", payload: { name: string; email: string; is_active: boolean }): void;
}>();

const form = reactive({
    name: props.initial?.name ?? "",
    email: props.initial?.email ?? "",
    is_active: props.initial?.is_active ?? true,
});

const onSubmit = () => {
    emit("submit", { ...form });
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="onSubmit">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1">
                <label class="text-sm font-medium">Nombre</label>
                <input v-model="form.name" class="h-10 w-full rounded-md border bg-background px-3 text-sm"
                    placeholder="Nombre completo" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Email</label>
                <input v-model="form.email" type="email"
                    class="h-10 w-full rounded-md border bg-background px-3 text-sm" placeholder="correo@dominio.com" />
            </div>

            <div class="flex items-center gap-2">
                <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4" />
                <label for="is_active" class="text-sm">Activo</label>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit"
                class="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground hover:bg-primary/90">
                {{ props.submitLabel ?? "Guardar" }}
            </button>
        </div>
    </form>
</template>
