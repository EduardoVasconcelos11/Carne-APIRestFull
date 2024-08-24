<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CarneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'valor_total' => 'required|numeric|min:0',
            'qtd_parcelas' => 'required|integer|min:1',
            'data_primeiro_vencimento' => 'required|date_format:Y-m-d',
            'periodicidade' => 'required|in:mensal,semanal',
            'valor_entrada' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.j
     *
     * @return array
     */
    public function messages()
    {
        return [
            'valor_total.required' => 'O valor total é obrigatório.',
            'valor_total.numeric' => 'O valor total deve ser numérico.',
            'valor_total.min' => 'O valor total deve ser maior ou igual a zero.',
            'qtd_parcelas.required' => 'A quantidade de parcelas é obrigatória.',
            'qtd_parcelas.integer' => 'A quantidade de parcelas deve ser um número inteiro.',
            'qtd_parcelas.min' => 'A quantidade de parcelas deve ser pelo menos 1.',
            'data_primeiro_vencimento.required' => 'A data do primeiro vencimento é obrigatória.',
            'data_primeiro_vencimento.date_format' => 'A data do primeiro vencimento deve estar no formato YYYY-MM-DD.',
            'periodicidade.required' => 'A periodicidade é obrigatória.',
            'periodicidade.in' => 'A periodicidade deve ser "mensal" ou "semanal".',
            'valor_entrada.numeric' => 'O valor de entrada deve ser numérico.',
            'valor_entrada.min' => 'O valor de entrada deve ser maior ou igual a zero.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 406));
    }
}
