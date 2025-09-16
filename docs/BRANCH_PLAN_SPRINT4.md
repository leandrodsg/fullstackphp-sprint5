# Plano de Branches - Sprint 4 (Preparação para Evolução)

Este documento organiza as melhorias internas do Sprint 4 para facilitar a futura migração para API REST no Sprint seguinte, sem antecipar Passport, roles ou mudanças de fluxo de UI.

## Ordem Recomendada de Execução
1. `refactor/form-requests`
2. `refactor/domain-model-methods`
3. `chore/api-resources-stubs`
4. `feat/password-policy`
5. `feat/email-verification`
6. `feat/subscription-billing-skeleton`
7. `test/base-scenarios`
8. `docs/category-clarification`
9. `docs/readme-prep`
10. `docs/commit-guidelines` (opcional)

---
## 1. Branch: `refactor/form-requests`
Objetivo: Extrair validações dos controllers para Form Requests reutilizáveis.

Tarefas:
- Criar `app/Http/Requests/ServiceStoreRequest.php`
- Criar `app/Http/Requests/ServiceUpdateRequest.php`
- Criar `app/Http/Requests/SubscriptionStoreRequest.php`
- Criar `app/Http/Requests/SubscriptionUpdateRequest.php`
- (Opcional) Criar `app/Http/Requests/RegisterUserRequest.php`
- Atualizar `ServiceController` para usar Requests
- Atualizar `SubscriptionController` para usar Requests
- Substituir `$request->validate()` por `$request->validated()`
- Garantir que regras de validação não mudem semanticamente

Commits sugeridos:
- `refactor: add service form requests`
- `refactor: add subscription form requests`
- `refactor: apply form requests to controllers`

---
## 2. Branch: `refactor/domain-model-methods`
Objetivo: Encapsular lógica em models e reduzir repetição de filtros.

Tarefas:
- Adicionar `scopeForUser` em `Service`
- Adicionar `scopeForUser` em `Subscription`
- Adicionar método `isDue()` em `Subscription`
- Adicionar método `advanceBillingDate()` em `Subscription`
- Atualizar controllers para usar scopes
- (Opcional) Método `ownedBy(User $user)` em `Service` ou `Subscription`

Commits sugeridos:
- `refactor: add model scopes for user filtering`
- `refactor: add subscription domain logic methods`
- `refactor: use scopes in controllers`

---
## 3. Branch: `chore/api-resources-stubs`
Objetivo: Preparar camada de serialização para futura API.

Tarefas:
- Criar `app/Http/Resources/ServiceResource.php`
- Criar `app/Http/Resources/SubscriptionResource.php`
- Criar `app/Http/Resources/UserResource.php`
- Definir campos explicitamente em `toArray()` (sem lógica complexa)
- Não referenciar ainda nas rotas ou controllers

Commits sugeridos:
- `chore: scaffold service resource`
- `chore: scaffold subscription resource`
- `chore: scaffold user resource`

---
## 4. Branch: `feat/password-policy`
Objetivo: Reforçar segurança de senha conforme feedback.

Tarefas:
- Criar `app/Rules/StrongPassword.php` (mín. 12 chars, upper, lower, number, special)
- Integrar rule na criação de usuários (via `RegisterUserRequest` ou diretamente)
- Mensagens de erro claras
- Ajustar documentação se necessário

Commits sugeridos:
- `feat: add StrongPassword rule`
- `feat: enforce strong password in registration`

Regex sugerida: `^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=\[\]{};:'",.<>?/\\|`~]).{12,}$`

---
## 5. Branch: `feat/email-verification`
Objetivo: Ativar infraestrutura de verificação de e-mail sem alterar drasticamente UX.

Tarefas:
- Adicionar `implements MustVerifyEmail` em `User`
- Confirmar existência das rotas padrão de verificação (Breeze)
- Testar envio de link em ambiente local
- Adicionar nota no README sobre ativação futura via middleware `verified`

Commits sugeridos:
- `feat: enable email verification support`
- `docs: note email verification behavior`

---
## 6. Branch: `feat/subscription-billing-skeleton`
Objetivo: Criar esqueleto da lógica de cobrança para evolução posterior.

Tarefas:
- Adicionar método `advanceOneCycle()` em `Subscription` (usa `addMonth()`)
- Reaproveitar ou alinhar com `advanceBillingDate()` se já existir
- Criar evento `SubscriptionBillingAdvanced`
- Criar listener stub `LogSubscriptionBillingAdvance`
- Não criar ainda comando ou scheduler

Commits sugeridos:
- `feat: add subscription cycle advance method`
- `feat: add billing advanced event and listener`

---
## 7. Branch: `test/base-scenarios`
Objetivo: Estabelecer base de testes para futura expansão (API/TDD).

Tarefas:
- Feature Test: criação de Service (sucesso + falha de validação)
- Feature Test: criação de Subscription (valores válidos)
- Unit Test: método `advanceBillingDate()` ou `advanceOneCycle()`
- Usar factories existentes

Commits sugeridos:
- `test: add service creation feature tests`
- `test: add subscription creation feature tests`
- `test: add subscription billing unit test`

---
## 8. Branch: `docs/category-clarification`
Objetivo: Responder feedback sobre categoria “Streaming”.

Tarefas:
- Adicionar seção "Categorias" no `README.md`
- Comentar no model `Service` significado das categorias
- Especificar exemplos: Streaming, SaaS, Infra, Música, Gaming

Commits sugeridos:
- `docs: clarify service categories`
- `docs: annotate Service model with category docs`

---