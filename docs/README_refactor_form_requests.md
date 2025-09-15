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

## Motivação / Benefícios
- Facilita mudança futura para respostas JSON (API) sem reescrever regras
- Permite reutilizar validações em múltiplos contextos
- Melhora legibilidade dos controllers
- Prepara terreno para regras mais complexas (ex: StrongPassword, role-based)

## Próximos Passos (outras branches)
1. `refactor/domain-model-methods`: encapsular lógica em models e scopes.
2. `chore/api-resources-stubs`: preparar serialização estruturada.
3. `feat/password-policy`: reforçar senha.

## Commits Sugeridos
(Use granularidade ao subir PR):
- `refactor: add service form requests and apply controller`
- `refactor: add subscription form requests and apply controller`
- `refactor: add register user form request`
- `test: add form requests coverage and service factory`

## Checklist de Conclusão
- [x] Form Requests criados
- [x] Controllers atualizados
- [x] Regras equivalentes às originais
- [x] Teste de criação de Service
- [x] Teste de criação de Subscription
- [x] Factory de Service adicionada

Pronto para PR -> base `develop`.
