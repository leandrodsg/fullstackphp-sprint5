# Branch: test/base-scenarios

## Objetivo
Estabelecer base de testes para futura expansão (API/TDD) e identificar gaps de validação.

## Testes Implementados

### Feature Tests - Service Creation
- Criação de service com dados válidos
- Falha com campos obrigatórios ausentes  
- Corrigido: Categoria inválida é rejeitada na validação
- Corrigido: Nome deve ser único por usuário
- Usuários diferentes podem ter services com mesmo nome
- Usuário não autenticado é rejeitado

### Feature Tests - Subscription Creation  
- Criação de subscription mensal e anual
- Falha com service inválido e campos ausentes
- Corrigido: Moeda inválida é rejeitada na validação
- Falha com preço inválido (mínimo 0.01)
- Corrigido: Usuário não pode usar service de outro usuário
- Usuário não autenticado é rejeitado

### Unit Tests - Billing Cycle
- advanceOneCycle() adiciona 1 mês para planos mensais
- advanceOneCycle() adiciona 1 ano para planos com "Annual"/"Yearly" 
- Detecção case-insensitive de planos anuais
- Planos sem "Annual"/"Yearly" defaultam para mensal
- advanceBillingDate() mantém compatibilidade (sempre +1 mês)
- Evento SubscriptionBillingAdvanced é disparado

### Feature Tests - User Data Isolation
- Usuários veem apenas seus próprios services/subscriptions
- Usuários não podem visualizar detalhes de outros usuários
- Usuários não podem editar/deletar resources de outros
- Model scopes funcionam corretamente

### 5. Melhorias Adicionais
- Preço mínimo de 0.01 (evita valores zero)
- Data de billing deve ser futura
- Security by obscurity (404 em vez de 403)

## Como executar

```bash
# Todos os novos testes (27 testes - 100% passando!)
php artisan test --filter="ServiceCreationTest|SubscriptionCreationTest|SubscriptionBillingCycleTest|UserDataIsolationTest"

# Resultado esperado: Tests: 27 passed (104 assertions)
```