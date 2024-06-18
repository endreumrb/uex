<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_contact_with_invalid_cpf()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contactData = [
            'nome' => 'John Doe',
            'cpf' => '12345678900', // CPF inválido
            'telefone' => '1234567890',
            'endereco' => 'Rua Exemplo, 123',
            'cidade' => 'Cidade Exemplo',
            'cep' => '12345678',
        ];

        $response = $this->post(route('contacts.store'), $contactData);

        $response->assertSessionHasErrors('cpf');
        $this->assertDatabaseMissing('contacts', ['cpf' => '12345678900']);
    }

    public function test_create_contact_with_duplicate_cpf()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Contact::factory()->create([
            'user_id' => $user->id,
            'cpf' => '12345678901',
        ]);

        $contactData = [
            'nome' => 'John Doe',
            'cpf' => '12345678901', // CPF duplicado
            'telefone' => '1234567890',
            'endereco' => 'Rua Exemplo, 123',
            'cidade' => 'Cidade Exemplo',
            'cep' => '12345678',
        ];

        $response = $this->post(route('contacts.store'), $contactData);

        $response->assertSessionHasErrors('cpf');
        $this->assertDatabaseCount('contacts', 1);
    }

    public function test_create_contact_with_missing_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contactData = [
            'nome' => 'John Doe',
            'cpf' => '12345678901',
            // Campos obrigatórios ausentes
        ];

        $response = $this->post(route('contacts.store'), $contactData);

        $response->assertSessionHasErrors(['telefone', 'endereco', 'cidade', 'cep']);
        $this->assertDatabaseMissing('contacts', ['cpf' => '12345678901']);
    }
}