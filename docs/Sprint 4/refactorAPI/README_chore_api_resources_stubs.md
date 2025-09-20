# Branch: chore/api-resources-stubs

## Objetivo
Criar API Resources básicos para preparar a futura migração para API REST no Sprint 5.

## Alterações Realizadas

### 1. ServiceResource (`app/Http/Resources/ServiceResource.php`)
- Resource para serializar dados do model Service
- Campos: id, name, category, description, website_url, created_at, updated_at
- Padroniza formato de retorno para APIs futuras

### 2. SubscriptionResource (`app/Http/Resources/SubscriptionResource.php`) 
- Resource para serializar dados do model Subscription
- Campos: id, plan, price, currency, status, next_billing_date, created_at, updated_at
- Controla quais dados da subscription são expostos via API

### 3. UserResource (`app/Http/Resources/UserResource.php`)
- Resource para serializar dados do model User
- Campos: id, name, email, email_verified_at, created_at, updated_at
- Garante que dados sensíveis (password, tokens) não sejam expostos

### 4. Testes (`tests/Feature/ApiResourcesTest.php`)
- Testes para validar estrutura dos Resources
- Cobertura: Campos obrigatórios, segurança (dados não expostos)
- Garantia de que serialização funciona corretamente

## Arquivos Modificados
- `app/Http/Resources/ServiceResource.php` (novo)
- `app/Http/Resources/SubscriptionResource.php` (novo)
- `app/Http/Resources/UserResource.php` (novo)
- `tests/Feature/ApiResourcesTest.php` (novo)

## Como Testar
```bash
# Executar testes específicos da branch
php artisan test tests/Feature/ApiResourcesTest.php