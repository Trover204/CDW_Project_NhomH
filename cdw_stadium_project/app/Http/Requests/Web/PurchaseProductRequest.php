<?php

namespace App\Http\Requests\Web;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Product|null $product */
        $product = $this->route('product');
        $maxStock = $product ? max(1, (int) $product->stock_quantity) : 1;

        return [
            'payment_method' => ['required', 'in:momo,visa,paypal,vnpay,cod,cash'],
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$maxStock],
        ];
    }
}
