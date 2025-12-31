<script setup lang="ts">
const props = defineProps<{
    open: boolean;
    title?: string;
    description?: string;
    confirmText?: string;
    cancelText?: string;
    loading?: boolean;
    destructive?: boolean;
}>();

const emit = defineEmits<{
    (e: "update:open", value: boolean): void;
    (e: "confirm"): void;
    (e: "cancel"): void;
}>();

const close = () => emit("update:open", false);

const onCancel = () => {
    emit("cancel");
    close();
};

const onConfirm = () => {
    emit("confirm");
};
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50">
        <!-- overlay -->
        <div class="absolute inset-0 bg-black/50" @click="onCancel" />

        <!-- dialog -->
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-lg border bg-background shadow-lg">
                <div class="p-5">
                    <h3 class="text-base font-semibold">
                        {{ title ?? "Confirmar acción" }}
                    </h3>
                    <p v-if="description" class="mt-2 text-sm text-muted-foreground">
                        {{ description }}
                    </p>

                    <div class="mt-5 flex justify-end gap-2">
                        <button type="button" class="h-10 rounded-md border px-4 text-sm hover:bg-accent"
                            :disabled="loading" @click="onCancel">
                            {{ cancelText ?? "Cancelar" }}
                        </button>

                        <button type="button" class="h-10 rounded-md px-4 text-sm text-white disabled:opacity-60"
                            :class="destructive ? 'bg-destructive hover:bg-destructive/90' : 'bg-primary hover:bg-primary/90'"
                            :disabled="loading" @click="onConfirm">
                            <span v-if="!loading">{{ confirmText ?? "Confirmar" }}</span>
                            <span v-else>Procesando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
