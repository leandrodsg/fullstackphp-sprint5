# Branch: feat/subscription-billing-skeleton

## Objetivo
Criar esqueleto da lógica de cobrança para evolução posterior sem alterar funcionamento atual.

## O que foi implementado

### Método advanceOneCycle()
- Adicionado no model Subscription
- Detecta automaticamente planos mensais vs anuais
- Planos com "Annual" ou "Yearly" no nome: +1 ano
- Outros planos (padrão): +1 mês
- Mantém compatibilidade com código atual

### Evento SubscriptionBillingAdvanced
- Evento simples em app/Events/
- Disparado quando um ciclo de cobrança avança
- Contém referência à subscription que avançou

### Listener LogSubscriptionBillingAdvance
- Listener stub que registra no log
- Registrado no AppServiceProvider
- Log inclui: subscription_id, user_id, service, nova data

## Como funciona
1. Chame subscription.advanceOneCycle()
2. Data de cobrança avança baseado no plano:
   - "Basic Annual", "Premium Yearly" → +1 ano
   - "Basic", "Premium", "Pro" → +1 mês (padrão)
3. Evento é disparado automaticamente
4. Log é registrado em storage/logs/laravel.log

## Exemplos de uso
```php
// Plano mensal - avança 1 mês
$subscription = new Subscription(['plan' => 'Basic']);
$subscription->advanceOneCycle();

// Plano anual - avança 1 ano
$subscription = new Subscription(['plan' => 'Premium Annual']);
$subscription->advanceOneCycle();
```