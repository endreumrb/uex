<?php

namespace App\Http\Requests;

use App\Rules\ValidCPF;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactCreateRequest extends FormRequest
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
        return [
            'nome' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'max:14', Rule::unique('contacts')->where('user_id', auth()->id()), new ValidCPF],
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
        ];
    }
}
