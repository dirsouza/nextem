<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ActivityRequest extends FormRequest
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
            'activity'      => 'required|min:5',
            'responsible'   => 'required|array',
            'responsible.*' => 'distinct|exists:users,id',
            'status'        => 'required|integer|exists:status,id',
            'deadline'      => 'required|date_format:Y-m-d'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'activity.required' => 'A :attribute é obrigatória',
            'required'          => 'O :attribute é obrigatório',
            'activity.min'      => 'A descrição da :attribute deve ter mais de :min caracteres',
            'array'             => 'O :attribute dever ser do tipo array',
            'distinct'          => 'O :attribute não pode ser duplicado',
            'integer'           => 'O :attribute deve ser do tipo inteiro',
            'date_format'       => 'O :attribute deve ser uma data válida no padrão Y-m-d',
            'exists'            => 'O :attribute não foi encontrado',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'activity'      => 'Atividade',
            'responsible'   => 'Responsável',
            'responsible.*' => 'Responsável sob o ID: :input',
            'status'        => 'Status',
            'deadline'      => 'Prazo'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response()->json([
            'success' => false,
            'message' => $validator->errors()->unique()
        ], 422));
    }
}
