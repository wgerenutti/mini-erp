<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Prepare the data for validation.
     *
     * Strips out thousands separators (dots) and converts the decimal
     * comma to a dot so that the `preco` field can be correctly
     * recognized as a numeric value by Laravel’s validator.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('preco')) {
            $preco = str_replace('.', '', $this->input('preco'));
            $preco = str_replace(',', '.', $preco);
            $this->merge(['preco' => $preco]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome'  => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'min:0'],
        ];
    }
}
