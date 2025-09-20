# README feat/auth-api-endpoints

Este documento descreve as implementações realizadas na branch feat/auth-api-endpoints do Sprint 5.

## Objetivo

Implementar endpoints de autenticação da API conforme documentação aprovada, permitindo registro, login, logout e gerenciamento de perfil via tokens de acesso.

## Passos realizados

1. Criação de testes automatizados para endpoints de autenticação
	- Arquivo: tests/Feature/AuthApiEndpointsTest.php
	- Testa todos os endpoints de autenticação e perfil.

2. Implementação do AuthController
	- Arquivo: app/Http/Controllers/Api/AuthController.php
	- Métodos implementados:
	  - register(): POST /api/v1/register (registro de usuários com validação)
	  - login(): POST /api/v1/login (autenticação com geração de token)
	  - logout(): POST /api/v1/logout (revogação de tokens do usuário)

3. Implementação do ProfileController
	- Arquivo: app/Http/Controllers/Api/ProfileController.php
	- Métodos implementados:
	  - profile(): GET /api/v1/profile (dados do usuário autenticado)
	  - changePassword(): PUT /api/v1/change-password (alteração de senha)

4. Configuração das rotas da API
	- Arquivo: routes/api.php
	- Rotas públicas: register e login
	- Rotas protegidas: profile, change-password e logout (middleware auth:api)
	- Rate limiting aplicado em endpoints sensíveis (throttle:api)

5. Middleware de autenticação
	- Aplicado middleware auth:api em todas as rotas protegidas
	- Garante que apenas usuários autenticados acessem recursos protegidos

6. Validação e tratamento de erros
	- Validação de dados de entrada em todos os endpoints
	- Respostas JSON padronizadas para sucesso e erro
	- Códigos de status HTTP apropriados (200, 201, 401, 422)

7. Rate limiting para segurança
	- Configurado throttle:api para endpoints de login e change-password
	- Proteção contra ataques de força bruta

## Teste automatizado

O teste está em tests/Feature/AuthApiEndpointsTest.php e cobre:
- Registro de usuário com dados válidos
- Falha no registro com email duplicado
- Login com credenciais válidas
- Falha no login com credenciais inválidas
- Acesso ao perfil com token válido
- Bloqueio de acesso ao perfil sem token
- Alteração de senha com dados válidos
- Falha na alteração com senha atual incorreta
- Logout com revogação de token

## Issue conhecido no logout

O método `token()->revoke()` do Laravel Passport apresenta limitações em ambiente de testes.

Referências do problema:
- https://stackoverflow.com/questions/65853851/laravel-passport-unit-test-for-logout-revoking-token
- https://github.com/laravel/passport/issues/446
- https://stackoverflow.com/questions/43318310/how-to-logout-a-user-from-api-using-laravel-passport
- https://laracasts.com/discuss/channels/laravel/passport-how-can-i-manually-revoke-access-token

Comando: php artisan test --filter=AuthApiEndpointsTest

## Correções de testes auxiliares

Durante o desenvolvimento, foram identificados e corrigidos 4 problemas que causavam falhas nos testes:

### 1. Handler.php - Erro "Undefined method 'getStatusCode'"
Problema: Código tentava chamar `getStatusCode()` em qualquer exceção sem verificação de tipo
Solução: Refatoração completa do método `render()` em `app/Exceptions/Handler.php`
- Adicionados imports corretos: `HttpException` e `Throwable`
- Substituída verificação `method_exists` por `instanceof HttpException`
- Implementada lógica type-safe para obter status code
- Mantém retorno JSON padronizado: `{"success": false, "message": "..."}`

### 2. Rota Fallback da API sem estrutura padronizada
Problema: Rota fallback retornava apenas `{'message': 'Not Found'}` 
Solução: Corrigida rota fallback em `routes/api.php`
- Alterado retorno para `{'success': false, 'message': 'Not Found'}`
- Mantém consistência com padrão de resposta da API

### 3. PassportAuthTest com rota incorreta
Problema: Teste chamava `/api/user` mas rota estava em `/api/v1/user`
Solução: Corrigida rota no teste `PassportAuthTest.php`
- Alterado de `/api/user` para `/api/v1/user`
- Mantém consistência com estrutura de rotas versionadas

### 4. RegistrationTest com senha inadequada
Problema: Teste usava senha "password" que não atendia aos requisitos da `StrongPassword`
Solução: Atualizada senha no teste `RegistrationTest.php`
- Alterado de "password" para "Password123!"
- Atende todos os critérios: 10+ caracteres, maiúscula, minúscula, número e caractere especial