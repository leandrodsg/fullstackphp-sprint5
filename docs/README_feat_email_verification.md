# Branch: feat/email-verification

## Objetivo
Ativar infraestrutura de verificação de e-mail sem alterar drasticamente UX.

## O que foi implementado
- Ativado MustVerifyEmail no model User:
  - Interface implementada para habilitar verificação de email
  
- Rotas já existentes (Laravel Breeze):
  - /verify-email - prompt de verificação
  - /email/verification-notification - reenvio de email
  - Controllers e middleware já configurados

## Como funciona
1. Cadastro atual continua igual - usuário entra direto
2. Email de verificação é enviado automaticamente após cadastro
3. Verificação opcional - usuário pode clicar no link quando quiser
4. Preparado para API - middleware verified disponível para futuras rotas

## Como testar localmente
1. Configure MAIL_MAILER=log no .env (já configurado)
2. Cadastre um novo usuário
3. Verifique o email no arquivo storage/logs/laravel.log
4. Acesse o link para verificar o email

## Configuração atual
- Emails salvos em log (MAIL_MAILER=log)
- Remetente configurado (noreply@techsubs.com)