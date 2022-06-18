<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\RetailerType;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreProductRetailerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Product $product */
        $product = $this->product; // https://www.csrhymes.com/2019/06/22/using-the-unique-validation-rule-in-laravel-form-request.html

        return [
            'url' => ['required', 'url'],
            'type' => [
                'required',
                new Enum(RetailerType::class),
                Rule::unique('product_retailers')->where(
                    fn (Builder $query) => $query->where('product_id', $product->id)
                ),
            ],
        ];
    }
}
