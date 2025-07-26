<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProdutoRequest extends FormRequest
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
            $p = str_replace('.', '', $this->input('preco'));
            $p = str_replace(',', '.', $p);
            $this->merge(['preco' => $p]);
        }

        if ($this->has('variacoes') && is_array($this->input('variacoes'))) {
            $variacoes = collect($this->input('variacoes'))->map(function ($var) {
                if (isset($var['preco'])) {
                    $p = str_replace('.', '', $var['preco']);
                    $p = str_replace(',', '.', $p);
                    $var['preco'] = $p;
                }
                return $var;
            })->toArray();

            $this->merge(['variacoes' => $variacoes]);
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
