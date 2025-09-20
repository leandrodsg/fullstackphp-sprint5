# README feat/subscriptions-api-endpoints

Este documento descreve as implementações realizadas na branch feat/subscriptions-api-endpoints do Sprint 5.

## Objetivo

Implementar CRUD completo de Subscriptions via API, incluindo funcionalidades específicas como cancelamento e reativação de assinaturas, aproveitando a base preparada no Sprint 4.

## Contexto importante

Esta implementação foi facilitada pelo trabalho preparatório realizado no Sprint 4, onde foram criados:
	- Migration da tabela subscriptions com estrutura completa
	- Modelo Subscription com relacionamentos e métodos de domínio
	- Factory para geração de dados de teste
	- Base de testes com estrutura funcional

## Passos realizados

1. Implementação do controller da API
	- Arquivo: app/Http/Controllers/Api/SubscriptionController.php
	- Criado controller com herança de BaseController
	- Implementados métodos CRUD completos: index, store, show, update, destroy
	- Adicionados métodos específicos: cancel, reactivate
	- Configurada autenticação e autorização por usuário com forUser()

2. Configuração das rotas da API
	- Arquivo: routes/api.php
	- Adicionadas rotas RESTful para subscriptions
	- Implementadas rotas específicas para cancelamento e reativação
	- Rotas protegidas com middleware auth:api (Passport)

3. Validação de dados
	- Criadas classes SubscriptionStoreRequest e SubscriptionUpdateRequest
	- Implementadas validações inline no controller
	- Configuradas regras para service_id, plan, price, currency, next_billing_date, status
	- Corrigidas validações para usar next_billing_date em vez de start_date

4. Correção de inconsistências
	- Ajustadas validações no controller para usar next_billing_date
	- Corrigidas factories para gerar dados consistentes
	- Sincronizados testes com as validações do controller
	- Garantida integridade referencial com service_id

5. Criação de testes abrangentes
	- Arquivo: tests/Feature/SubscriptionApiEndpointsTest.php
	- 12 testes cobrindo todos os cenários:
	  - Listagem de assinaturas do usuário autenticado
	  - Criação de assinaturas com validação de dados
	  - Visualização de assinatura individual
	  - Atualização de assinaturas
	  - Exclusão de assinaturas
	  - Cancelamento e reativação de assinaturas
	  - Proteção contra acesso não autenticado
	  - Isolamento de dados entre usuários

## Endpoints implementados

GET /api/v1/subscriptions - Lista assinaturas do usuário autenticado
POST /api/v1/subscriptions - Cria nova assinatura
GET /api/v1/subscriptions/{id} - Visualiza assinatura específica
PUT /api/v1/subscriptions/{id} - Atualiza assinatura
DELETE /api/v1/subscriptions/{id} - Remove assinatura
PATCH /api/v1/subscriptions/{id}/cancel - Cancela assinatura
PATCH /api/v1/subscriptions/{id}/reactivate - Reativa assinatura

## Campos obrigatórios

Criação: service_id, plan, price, currency, next_billing_date, status
Atualização: plan, price, currency, next_billing_date, status

## Teste automatizado

Todos os 12 testes passaram com sucesso, validando:
	- Funcionalidade completa do CRUD
	- Funcionalidades específicas de cancelamento e reativação
	- Autenticação via Passport
	- Isolamento de dados por usuário
	- Validação de entrada de dados
	- Estrutura de resposta JSON padronizada