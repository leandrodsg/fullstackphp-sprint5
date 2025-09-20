# Branch: refactor/domain-model-methods

## Objetivo
Encapsular lógica de domínio nos models para reduzir repetição de código e criar uma base mais consistente para futura migração para API.

## Alterações Realizadas

### 1. Model Service (`app/Models/Service.php`)
- Adicionado: `scopeForUser()` - scope para filtrar services pelo usuário logado ou específico
- Elimina repetição de `where('user_id', Auth::id())` nos controllers

### 2. Model Subscription (`app/Models/Subscription.php`)
- Adicionado: `scopeForUser()` - scope para filtrar subscriptions pelo usuário logado ou específico
- Adicionado: `isDue()` - método para verificar se a subscription está vencida (past billing date)
- Adicionado: `advanceBillingDate()` - método para avançar a data de cobrança em um mês
- Encapsula lógica de domínio no model, preparando para automações futuras

### 3. ServiceController (`app/Http/Controllers/ServiceController.php`)
- Refatorado: Todos os métodos agora usam `Service::forUser()` em vez de `where('user_id', Auth::id())`
- Métodos alterados: `index()`, `show()`, `edit()`, `update()`, `destroy()`
- Código mais limpo e menos repetitivo

### 4. SubscriptionController (`app/Http/Controllers/SubscriptionController.php`)
- Refatorado: Todos os métodos agora usam `Subscription::forUser()` em vez de `where('user_id', Auth::id())`
- Refatorado: Métodos `create()` e `edit()` agora mostram apenas services do usuário logado
- Métodos alterados: `index()`, `create()`, `show()`, `edit()`, `update()`, `destroy()`
- Código mais limpo e seguro (usuário só vê seus próprios services)

### 5. Testes (`tests/Feature/DomainModelMethodsTest.php`)
- Criado: Testes para validar funcionamento dos scopes e métodos de domínio
- Cobertura: `scopeForUser` nos dois models, `isDue()` e `advanceBillingDate()`
- Garantia de que refatoração não quebra funcionalidades

## Arquivos Modificados
- `app/Models/Service.php`
- `app/Models/Subscription.php`
- `app/Http/Controllers/ServiceController.php`
- `app/Http/Controllers/SubscriptionController.php`
- `tests/Feature/DomainModelMethodsTest.php` (novo)

## Como Testar
```bash
# Executar testes específicos da branch
php artisan test tests/Feature/DomainModelMethodsTest.php