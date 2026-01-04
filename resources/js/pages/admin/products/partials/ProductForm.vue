<script setup lang="ts">
import { reactive } from "vue";

import FormErrors from "@/components/shared/FormErrors.vue";
import LoadingButton from "@/components/shared/LoadingButton.vue";

import type { OptionItem } from "@/types/admin/products";
import type { ProductFormPayload } from "@/types/admin/products-form";

const props = defineProps<{
    categories: OptionItem[];
    brands: OptionItem[];
    initial?: Partial<ProductFormPayload>;
    submitLabel?: string;
    loading?: boolean;
}>();

const emit = defineEmits<{
    (e: "submit", payload: ProductFormPayload): void;
}>();

const form = reactive<ProductFormPayload>({
    category_id: props.initial?.category_id ?? (props.categories[0]?.id ?? 0),
    brand_id: props.initial?.brand_id ?? null,
    sku: props.initial?.sku ?? "",
    name: props.initial?.name ?? "",
    description: props.initial?.description ?? null,
    price: props.initial?.price ?? "0.00",
    currency: props.initial?.currency ?? "PEN",
    stock_on_hand: props.initial?.stock_on_hand ?? 0,
    is_active: props.initial?.is_active ?? true,
});

const onSubmit = () => {
    emit("submit", {
        ...form,
        stock_on_hand: Number(form.stock_on_hand ?? 0),
        brand_id: form.brand_id === null || form.brand_id === ("" as any) ? null : Number(form.brand_id),
        category_id: Number(form.category_id),
        price: String(form.price ?? "0"),
    });
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="onSubmit">
        <FormErrors />

        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1">
                <label class="text-sm font-medium">Categoría</label>
                <select v-model="form.category_id" class="h-10 w-full rounded-md border bg-background px-3 text-sm">
                    <option v-for="c in props.categories" :key="c.id" :value="c.id">
                        {{ c.name }}
                    </option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Marca (opcional)</label>
                <select v-model="form.brand_id" class="h-10 w-full rounded-md border bg-background px-3 text-sm">
                    <option :value="null">— Ninguna —</option>
                    <option v-for="b in props.brands" :key="b.id" :value="b.id">
                        {{ b.name }}
                    </option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">SKU</label>
                <input v-model="form.sku" class="h-10 w-full rounded-md border bg-background px-3 text-sm"
                    placeholder="Ej: TCG-POK-001" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Nombre</label>
                <input v-model="form.name" class="h-10 w-full rounded-md border bg-background px-3 text-sm"
                    placeholder="Ej: Booster Pokémon Scarlet & Violet" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Precio (S/)</label>
                <input v-model="form.price" inputmode="decimal"
                    class="h-10 w-full rounded-md border bg-background px-3 text-sm" placeholder="Ej: 19.90" />
                <p class="text-xs text-muted-foreground">Se guardará en centavos (price_amount).</p>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Stock inicial</label>
                <input v-model.number="form.stock_on_hand" type="number" min="0"
                    class="h-10 w-full rounded-md border bg-background px-3 text-sm" />
            </div>

            <div class="space-y-1 md:col-span-2">
                <label class="text-sm font-medium">Descripción (opcional)</label>
                <textarea v-model="form.description" rows="4"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                    placeholder="Detalles del producto..." />
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
