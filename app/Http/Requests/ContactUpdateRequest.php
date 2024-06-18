<?php

namespace App\Http\Requests;

use App\Rules\ValidCPF;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactUpdateRequest extends FormRequest
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
        $id = $this->route('contact'); // Obtém o ID do contato sendo atualizado
    
        return [
            'nome' => 'required|string|max:255',
            'cpf' => [
                'required',
                'string',
                'max:14',
                Rule::unique('contacts', 'cpf')->ignore($id)->where('user_id', auth()->id()),
                new ValidCPF,
            ],
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
        ];
    }
}
