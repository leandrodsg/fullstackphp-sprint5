# README feat/services-api-endpoints

Este documento descreve as implementações realizadas na branch feat/services-api-endpoints do Sprint 5.

## Objetivo

Implementar CRUD completo de Services via API, aproveitando a base preparada no Sprint 4 com API Resources, Form Requests e métodos de domínio já estruturados.

## Contexto importante

Esta implementação foi facilitada pelo trabalho preparatório realizado no Sprint 4, onde foram criados:
	- API Resources (ServiceResource) para serialização padronizada
	- Form Requests (ServiceStoreRequest, ServiceUpdateRequest) para validação
	- Métodos de domínio como forUser() para filtros por usuário
	- Base de testes com factories funcionais

## Passos realizados

1. Verificação da implementação existente
	- Arquivo: app/Http/Controllers/Api/ServiceController.php
	- Confirmado que todos os métodos CRUD já estavam implementados
	- Métodos disponíveis: index, store, show, update, partialUpdate, destroy

2. Configuração das rotas completas
	- Arquivo: routes/api.php
	- Adicionadas todas as rotas CRUD para services
	- Implementado endpoint GET /api/v1/categories para listar categorias únicas
	- Rotas protegidas com middleware auth:api (Passport)

3. Validação da autenticação Passport
	- Confirmado que o guard api estava configurado com driver passport
	- Verificado que o Passport estava instalado e configurado corretamente
	- AppServiceProvider com configurações de expiração de tokens

4. Criação de testes abrangentes
	- Arquivo: tests/Feature/ServiceApiEndpointsTest.php
	- 14 testes cobrindo todos os cenários:
	  - Listagem de serviços com filtros por nome e categoria
	  - Criação de serviços com validação de dados
	  - Visualização de serviço individual
	  - Atualização completa e parcial de serviços
	  - Exclusão de serviços
	  - Listagem de categorias
	  - Proteção contra acesso não autenticado
	  - Isolamento de dados entre usuários

## Endpoints implementados

GET /api/v1/services - Lista serviços do usuário autenticado
POST /api/v1/services - Cria novo serviço
GET /api/v1/services/{id} - Visualiza serviço específico
PUT /api/v1/services/{id} - Atualiza serviço completo
PATCH /api/v1/services/{id} - Atualiza serviço parcialmente
DELETE /api/v1/services/{id} - Remove serviço
GET /api/v1/categories - Lista categorias únicas dos serviços

## Filtros disponíveis

name - Filtra serviços por nome (busca parcial)
category - Filtra serviços por categoria exata

## Teste automatizado

Todos os 14 testes passaram com sucesso, validando:
	- Funcionalidade completa do CRUD
	- Autenticação via Passport
	- Isolamento de dados por usuário
	- Validação de entrada de dados
	- Estrutura de resposta JSON padronizada