# Branch: refactor/form-requests

## Objetivo
Extrair validações inline dos controllers e centralizar em Form Requests para facilitar manutenção, testes e futura conversão para API.

## Alterações Principais
- Adicionados Form Requests:
  - `app/Http/Requests/ServiceStoreRequest.php`
  - `app/Http/Requests/ServiceUpdateRequest.php`
  - `app/Http/Requests/SubscriptionStoreRequest.php`
  - `app/Http/Requests/SubscriptionUpdateRequest.php`
  - `app/Http/Requests/Auth/RegisterUserRequest.php`
- Atualizados controllers:
  - `ServiceController@store` / `update`
  - `SubscriptionController@store` / `update`
  - `RegisteredUserController@store`
- Adicionado suporte a factories para `Service`:
  - `HasFactory` no model `Service`
  - `database/factories/ServiceFactory.php`
- Criado teste de validação abrangendo fluxo de criação:
  - `tests/Feature/RefactorFormRequestsTest.php`

## Regras de Validação Mantidas
Service/Update:
- `name`: required|string|max:255
- `category`: required|string|max:100
- `description`: nullable|string
- `website_url`: nullable|url

Subscription:
- `service_id`: required|exists:services,id
- `plan`: required|string|max:100
- `price`: required|numeric|min:0
- `currency`: required|string|max:3
- `next_billing_date`: required|date
- `status`: required|in:active,cancelled

Register User:
- `name`: required|string|max:255
- `email`: required|email|unique:users
- `password`: required|confirmed (defaults Laravel)

## Testes Implementados
Arquivo: `tests/Feature/RefactorFormRequestsTest.php`
Cobre:
- Erros de validação ao criar Service sem campos obrigatórios
- Sucesso ao criar Service
- Erros de validação ao criar Subscription sem campos obrigatórios
- Sucesso ao criar Subscription