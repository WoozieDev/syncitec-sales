<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],

            'sku' => ['required', 'string', 'max:80', "unique:products,sku,{$productId}"],
            'name' => ['required', 'string', 'min:2', 'max:180'],
            'description' => ['nullable', 'string'],

            'price' => ['required'],
            'currency' => ['required', 'string', 'size:3'],

            'stock_on_hand' => ['required', 'integer', 'min:0', 'max:100000'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sku' => is_string($this->sku) ? trim($this->sku) : $this->sku,
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
            'brand_id' => $this->brand_id === '' ? null : $this->brand_id,
            'currency' => $this->currency ?: 'PEN',
        ]);
    }
}
