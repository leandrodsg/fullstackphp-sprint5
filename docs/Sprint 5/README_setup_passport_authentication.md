# README setup/passport-authentication

Este documento descreve as configurações e implementações realizadas na branch setup/passport-authentication do Sprint 5.

## Objetivo

Configurar a autenticação de API usando Laravel Passport, garantindo que o sistema aceite autenticação por token para as próximas etapas do projeto.

## Passos realizados


1. Instalação do pacote Laravel Passport via Composer
	- Comando executado:
	  composer require laravel/passport

2. Instalação e configuração inicial do Passport
	- Comando executado:
	  php artisan install:api --passport

3. Atualização do model User
	- Arquivo alterado: app/Models/User.php
	- Mudanças:
	  - Adicionada a interface OAuthenticatable na declaração da classe.
	  - Adicionado o trait HasApiTokens no bloco de traits.
	- Permite que o usuário use autenticação por token do Passport.

4. Configuração do guard api
	- Arquivo alterado: config/auth.php
	- Mudanças:
	  - Adicionado o guard 'api' com driver 'passport'.
	-Define que as rotas protegidas pelo guard 'api' usam autenticação via Passport.

5. Criação de teste automatizado
	- Arquivo criado: tests/Feature/PassportAuthTest.php
	- Testa se um usuário autenticado com Passport consegue acessar uma rota protegida (/api/user).

6. Geração da chave de aplicação (APP_KEY)
	- Comando executado:
	  php artisan key:generate
	- Gera a chave de criptografia usada pelo Laravel para segurança dos dados.

## Teste automatizado

O teste criado está localizado em tests/Feature/PassportAuthTest.php. Ele verifica se um usuário autenticado com Passport consegue acessar a rota /api/user.