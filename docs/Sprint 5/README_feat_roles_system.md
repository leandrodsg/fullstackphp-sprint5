# README feat/roles-system

Este documento descreve as implementações realizadas na branch feat/roles-system do Sprint 5.

## Objetivo

Adicionar um sistema simples de roles (user/admin) para usuários, permitindo diferenciação de permissões no sistema.

## Passos realizados


1. Criado teste automatizado para roles
	- Arquivo: tests/Feature/UserRoleTest.php

2. Migration para coluna 'role' na tabela users
	- Comando: php artisan make:migration add_role_to_users_table --table=users
	- Arquivo: database/migrations/xxxx_xx_xx_xxxxxx_add_role_to_users_table.php
	- Adiciona a coluna 'role' (string, default 'user') na tabela users.

3. Definidas constantes de roles no User
	- Arquivo: app/Models/User.php
	- Adiciona as constantes ROLE_USER e ROLE_ADMIN para padronizar os valores possíveis.

4. Implementado método hasRole() no User
	- Arquivo: app/Models/User.php
	- Método simples que retorna true se o usuário tem o role informado.

5. Criado middleware CheckRole
	- Comando: php artisan make:middleware CheckRole
	- Arquivo: app/Http/Middleware/CheckRole.php
	- Protege rotas exigindo que o usuário autenticado tenha o role especificado.

6. Atualizada a UserFactory para roles
	- Arquivo: database/factories/UserFactory.php
	- Permite criar usuários com role user ou admin, default 'user'.

7. Atualizado o DatabaseSeeder
	- Arquivo: database/seeders/DatabaseSeeder.php
	- Cria um admin padrão (admin@example.com, senha admin123) e três usuários comuns para testes.

8. Testes automatizados
	- Comando: php artisan test --filter=UserRoleTest
	- Garante que tudo está funcionando corretamente.

## Teste automatizado

O teste está em tests/Feature/UserRoleTest.php e cobre:
- Usuário tem role 'user' por padrão
- Pode criar usuário admin
- Método hasRole() funciona corretamente