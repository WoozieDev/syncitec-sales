<script setup lang="ts">
import { reactive } from "vue";

import FormErrors from "@/components/shared/FormErrors.vue";
import LoadingButton from "@/components/shared/LoadingButton.vue";

import type { RoleOption } from "@/types/admin/users";

const props = defineProps<{
    roles: RoleOption[];
    initial?: {
        name?: string;
        email?: string;
        is_active?: boolean;
        role?: string;
    };
    submitLabel?: string;
    loading?: boolean;
}>();

const emit = defineEmits<{
    (e: "submit", payload: {
        name: string;
        email: string;
        password: string;
        is_active: boolean;
        role: string;
    }): void;
}>();

const form = reactive({
    name: props.initial?.name ?? "",
    email: props.initial?.email ?? "",
    password: "",
    is_active: props.initial?.is_active ?? true,
    role: props.initial?.role ?? "",
});

const onSubmit = () => {
    emit("submit", { ...form });
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="onSubmit">
        <FormErrors />

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

            <div class="space-y-1">
                <label class="text-sm font-medium">Rol</label>
                <select v-model="form.role" class="h-10 w-full rounded-md border bg-background px-3 text-sm">
                    <option value="" disabled>Selecciona un rol</option>
                    <option v-for="r in props.roles" :key="r.id" :value="r.name">
                        {{ r.name }}
                    </option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Contraseña</label>
                <input v-model="form.password" type="password"
                    class="h-10 w-full rounded-md border bg-background px-3 text-sm"
                    placeholder="Mínimo 8 caracteres" />
                <p class="text-xs text-muted-foreground">
                    En edición, si no quieres cambiarla, la dejaremos opcional (Paso 6).
                </p>
            </div>

            <div class="flex items-center gap-2 md:col-span-2">
                <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4" />
                <label for="is_active" class="text-sm">Activo</label>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <LoadingButton type="submit" :loading="props.loading" variant="primary">
                {{ props.submitLabel ?? "Guardar" }}
            </LoadingButton>
        </div>
    </form>
</template>
