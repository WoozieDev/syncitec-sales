<script setup lang="ts">
import { computed, reactive } from "vue";
import FormErrors from "@/components/shared/FormErrors.vue";
import LoadingButton from "@/components/shared/LoadingButton.vue";

type PermissionOption = { id: number; name: string };

const props = defineProps<{
    permissions: PermissionOption[];
    initial?: {
        name?: string;
        permissions?: string[];
    };
    submitLabel?: string;
    loading?: boolean;
}>();

const emit = defineEmits<{
    (e: "submit", payload: { name: string; permissions: string[] }): void;
}>();

const form = reactive({
    name: props.initial?.name ?? "",
    permissions: new Set<string>(props.initial?.permissions ?? []),
});

const permissionGroups = computed(() => {
    const groups: Record<string, PermissionOption[]> = {};

    for (const p of props.permissions) {
        const module = p.name.includes(".") ? p.name.split(".")[0] : "other";
        groups[module] ||= [];
        groups[module].push(p);
    }

    return Object.entries(groups).sort(([a], [b]) => a.localeCompare(b));
});

const toggle = (name: string) => {
    if (form.permissions.has(name)) form.permissions.delete(name);
    else form.permissions.add(name);
};

const onSubmit = () => {
    emit("submit", {
        name: form.name,
        permissions: Array.from(form.permissions),
    });
};
</script>

<template>
    <form class="space-y-6" @submit.prevent="onSubmit">
        <FormErrors />

        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1">
                <label class="text-sm font-medium">Nombre del rol</label>
                <input v-model="form.name" class="h-10 w-full rounded-md border bg-background px-3 text-sm"
                    placeholder="ej: manager" />
                <p class="text-xs text-muted-foreground">
                    Recomendado: minúsculas, sin espacios.
                </p>
            </div>
        </div>

        <div class="rounded-lg border bg-card p-4">
            <div class="mb-3">
                <div class="text-sm font-semibold">Permisos</div>
                <p class="text-xs text-muted-foreground">
                    Selecciona los permisos que tendrá este rol.
                </p>
            </div>

            <div class="space-y-5">
                <div v-for="[module, perms] in permissionGroups" :key="module" class="space-y-2">
                    <div class="text-sm font-medium capitalize">{{ module }}</div>

                    <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-3">
                        <label v-for="p in perms" :key="p.id"
                            class="flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:bg-accent">
                            <input type="checkbox" class="h-4 w-4" :checked="form.permissions.has(p.name)"
                                @change="toggle(p.name)" />
                            <span class="text-muted-foreground">{{ p.name }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <LoadingButton type="submit" :loading="props.loading" variant="primary">
                {{ props.submitLabel ?? "Guardar" }}
            </LoadingButton>
        </div>
    </form>
</template>
