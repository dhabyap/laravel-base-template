<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Product filter. Used for store + update (update: partial fields allowed).
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth + role checked by route middleware, not here
    }

    public function rules(): array
    {
        // For PUT/PATCH (update), all fields optional.
        $sometimes = $this->isMethod('put') || $this->isMethod('patch') ? 'sometimes|' : '';

        return [
            'name' => $sometimes.'required|string|max:100',
            'price' => $sometimes.'required|integer|min:0|max:1000000000',
            'stock' => $sometimes.'required|integer|min:0|max:1000000',
        ];
    }
}
