# Scripts de Utilitários - Laravel Passport

## update-passport-keys.ps1

Script PowerShell para facilitar a atualização das chaves RSA do Laravel Passport no ambiente de produção.

### Uso Básico

```powershell
# Converter chaves existentes para Base64
.\scripts\update-passport-keys.ps1

# Gerar novas chaves e converter para Base64
.\scripts\update-passport-keys.ps1 -GenerateNew

# Gerar novas chaves e atualizar render.yaml automaticamente
.\scripts\update-passport-keys.ps1 -GenerateNew -UpdateRender
```

### Parâmetros

- `-GenerateNew`: Gera novas chaves RSA usando `php artisan passport:keys --force`
- `-UpdateRender`: Atualiza automaticamente o arquivo `render.yaml` com as novas chaves
- `-PrivateKeyPath`: Caminho personalizado para a chave privada (padrão: `storage\oauth-private.key`)
- `-PublicKeyPath`: Caminho personalizado para a chave pública (padrão: `storage\oauth-public.key`)

### Cenários de Uso

#### 1. Primeira Configuração
```powershell
# Se as chaves não existem ainda
.\scripts\update-passport-keys.ps1 -GenerateNew -UpdateRender
```

#### 2. Rotação de Chaves
```powershell
# Para gerar novas chaves por segurança
.\scripts\update-passport-keys.ps1 -GenerateNew
# Depois copie manualmente as variáveis para o Render
```

#### 3. Atualização do Ambiente
```powershell
# Se você já tem as chaves e só precisa das variáveis Base64
.\scripts\update-passport-keys.ps1
```

### Saída do Script

O script produz:
1. **Variáveis de ambiente** prontas para copiar para o Render:
   ```
   PASSPORT_PRIVATE_KEY=<base64_encoded_key>
   PASSPORT_PUBLIC_KEY=<base64_encoded_key>
   ```

2. **Backup automático** do `render.yaml` (quando usando `-UpdateRender`)

3. **Instruções** dos próximos passos

### Troubleshooting

**Erro: "Arquivo artisan não encontrado"**
- Execute o script na raiz do projeto Laravel

**Erro: "Chaves não encontradas"**
- Use o parâmetro `-GenerateNew` para gerar novas chaves
- Ou execute manualmente: `php artisan passport:keys --force`

**Erro: "Arquivo render.yaml não encontrado"**
- Certifique-se de que está na raiz do projeto
- O parâmetro `-UpdateRender` só funciona se o arquivo existir