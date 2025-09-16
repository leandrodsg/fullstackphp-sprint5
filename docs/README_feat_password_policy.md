# Branch: feat/password-policy

## Objetivo
Reforçar a segurança de senha no cadastro de usuários, conforme feedback do professor, exigindo critérios mínimos claros e didáticos.

## O que foi implementado
- Regra customizada de senha forte (`app/Rules/StrongPassword.php`):
  - Mínimo de 10 caracteres
  - Pelo menos uma letra maiúscula
  - Pelo menos uma letra minúscula
  - Pelo menos um número
  - Pelo menos um caractere especial (!@#$%&*)
- Integração da regra na validação de registro de usuários (`RegisterUserRequest`).
- Mensagens de erro claras e didáticas para cada critério.
- Testes automatizados:
  - Testes unitários para cada critério da regra
  - Teste de integração no fluxo de registro

## Como testar
1. Rode os testes da feature:
   ```bash
   php artisan test --filter=StrongPasswordTest
   ```
2. Tente registrar um usuário com senha fraca e veja a mensagem de erro.
3. Tente registrar com senha forte e confirme o sucesso.