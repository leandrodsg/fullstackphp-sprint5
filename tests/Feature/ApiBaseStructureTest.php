<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class ApiBaseStructureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_base_controller_should_return_json_success_response()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Este teste verifica se o BaseController está retornando
        // responses JSON padronizadas
        
        // Por enquanto vamos testar uma rota simples que deve existir
        $response = $this->getJson('/api/v1/test-base-response');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertJson([
                'success' => true
            ]);
    }

    /** @test */
    public function api_should_return_standardized_error_response()
    {
        // Testa se errors são retornados no formato JSON padronizado
        $response = $this->getJson('/api/v1/non-existent-route');

        $response->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'message'
            ])
            ->assertJson([
                'success' => false
            ]);
    }

    /** @test */
    public function api_should_require_authentication_for_protected_routes()
    {
        // Testa se rotas protegidas retornam 401 sem autenticação
        $response = $this->getJson('/api/v1/services');

        $response->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}