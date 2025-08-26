<?php

namespace Modules\Invoices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255', 'distinct'],
            'customer_email' => ['required', 'email', 'max:255', 'distinct'],
            'product_lines' => ['sometimes', 'array'],
            'product_lines.*.product_name' => ['required_with:product_lines', 'string', 'max:255','distinct'],
            'product_lines.*.quantity' => ['required_with:product_lines', 'integer', 'min:1'],
            'product_lines.*.price' => ['required_with:product_lines', 'integer', 'min:1'],
        ];
    }
}
