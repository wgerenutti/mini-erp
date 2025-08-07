<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCupomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $data = $this->all();

        // remover formatação de R$ e pontos e trocar vírgula por ponto
        if (isset($data['valor_desc'])) {
            $data['valor_desc'] = str_replace(
                ['R$', '.', '%', ' '],
                '',
                $data['valor_desc']
            );
            $data['valor_desc'] = str_replace(',', '.', $data['valor_desc']);
        }

        if (isset($data['minimo'])) {
            $data['minimo'] = str_replace(['R$', '.', ' '], '', $data['minimo']);
            $data['minimo'] = str_replace(',', '.', $data['minimo']);
        }

        // remover sufixo % e tratar vírgula
        if (isset($data['pct_desc'])) {
            $data['pct_desc'] = str_replace(['%', ' '], '', $data['pct_desc']);
            $data['pct_desc'] = str_replace(',', '.', $data['pct_desc']);
        }

        // checkbox vira boolean
        $data['ativo'] = $this->has('ativo') ? true : false;

        $this->merge($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo'     => 'required|string|unique:cupons,codigo',
            'valor_desc' => 'nullable|numeric',
            'pct_desc'   => 'nullable|numeric',
            'minimo'     => 'required|numeric',
            'valid_from' => 'nullable|date',
            'valid_to'   => 'nullable|date|after_or_equal:valid_from',
            'validade'   => 'required|date',
            'uso_maximo' => 'nullable|integer|min:1',
            'ativo'      => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'codigo.required'      => 'O campo código é obrigatório.',
            'codigo.unique'        => 'Este código já está em uso.',
            'valor_desc.numeric'   => 'O desconto fixo deve ser um número.',
            'pct_desc.numeric'     => 'O desconto em % deve ser um número.',
            'minimo.required'      => 'O valor mínimo é obrigatório.',
            'minimo.numeric'       => 'O valor mínimo deve ser um número.',
            'minimo.min'           => 'O valor mínimo não pode ser negativo.',
            'validade.required'    => 'A data de validade é obrigatória.',
            'validade.date'        => 'A data de validade deve ser uma data válida.',
            'valid_from.date'      => 'A data de início deve ser uma data válida.',
            'valid_to.date'        => 'A data de fim deve ser uma data válida.',
            'valid_to.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
            'uso_maximo.integer'   => 'O limite de uso deve ser um número inteiro.',
            'uso_maximo.min'       => 'O limite de uso deve ser, no mínimo, 1.',
            'ativo.boolean'        => 'O campo ativo deve ser verdadeiro ou falso.',
        ];
    }
}
