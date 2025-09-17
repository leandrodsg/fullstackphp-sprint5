# README refactor/api-controllers-base

Este documento descreve as implementações realizadas na branch refactor/api-controllers-base do Sprint 5.

## Objetivo

Criar a base da API versionada (v1) substituindo a camada MVC voltada para views por controllers que retornam JSON padronizado.

## Passos realizados

1. Criação da estrutura de rotas API versionada
	- Arquivo: `routes/api.php`
	- Adicionada a rota base sob prefixo `/api/v1`.
	- Incluído endpoint simples `/api/v1/test-base-response` para validar estrutura.
	- Adicionado fallback JSON para rotas inexistentes dentro de `v1` retornando `{ success: false, message: 'Not Found' }`.

2. Criação de BaseController para respostas padronizadas
	- Arquivo: `app/Http/Controllers/Api/BaseController.php`
	- Métodos simples para sucesso/erro (ou uso direto de `response()->json()`).

3. Conversão de ServiceController para API
	- Novo arquivo: `app/Http/Controllers/Api/ServiceController.php`
	- Removeu dependência de views/redirecionamentos.
	- Endpoints atuais focados em retorno JSON básico.

4. Conversão de SubscriptionController para API
	- Novo arquivo: `app/Http/Controllers/Api/SubscriptionController.php`
	- Mesmo padrão simples aplicado ao controller de subscriptions.

5. Rotas protegidas mínimas
	- Grupo protegido com `auth:api` para `/services` e `/subscriptions` (método `index` nesta fase).
	- Garante retorno 401 adequado quando sem autenticação.

6. Padronização de respostas de erro base
	- 404: via `Route::fallback()` específico dentro do prefixo `v1`.
	- 401: resposta padrão do guard Passport (aceita pelo teste).

7. Limpeza de lógica de views anterior
	- Controllers antigos de camada web não são mais usados na rota de API.
	- Nenhum redirect permanece no fluxo API.

8. Teste automatizado da estrutura base
	- Arquivo: `tests/Feature/ApiBaseStructureTest.php`
		- Resposta de sucesso JSON padronizada (`/api/v1/test-base-response`).
		- Resposta 404 padronizada para rota inexistente.
		- Proteção (401) ao acessar rota protegida sem token.
	- Execução: `php artisan test --filter=ApiBaseStructureTest`

9. Decisão sobre API Resources (ADIADO)
	- Embora existam stubs/menções a `ServiceResource`, `SubscriptionResource` e `UserResource`
	- Resources serão introduzidos nas branches `feat/services-api-endpoints` e `feat/subscriptions-api-endpoints`

## Situação do Handler de Exceções

- 404 tratado via fallback; 401 tratado pelo guard.

## Endpoints atuais expostos nesta fase

Lista textual (sem tabela para facilitar exportação / diffs):

- GET `/api/v1/test-base-response`
	- Público (não exige token)
	- validar rapidamente que a estrutura base da API está funcional
	- `{ success: true, message: 'API base structure working', data: null }`

- GET `/api/v1/services`
	- Protegido (`auth:api`)
	- Será expandido em `feat/services-api-endpoints` (CRUD completo + filtros + Resources)

- GET `/api/v1/subscriptions`
	- Protegido (`auth:api`)
	- Será expandido em `feat/subscriptions-api-endpoints` (CRUD, cancel/reactivate, filtros, Resources)

- Fallback `/api/v1/*` (qualquer rota inexistente dentro do prefixo)
	- Retorno: `{ success: false, message: 'Not Found' }` com HTTP 404
	- Garante consistência básica de erro sem precisar customizar Handler agora