# Script para atualizar as chaves do Laravel Passport
# Este script facilita a atualização das chaves RSA do Passport no ambiente de produção

param(
    [switch]$GenerateNew,
    [switch]$UpdateRender,
    [string]$PrivateKeyPath = "storage\oauth-private.key",
    [string]$PublicKeyPath = "storage\oauth-public.key"
)

Write-Host "=== Script de Atualização das Chaves Laravel Passport ===" -ForegroundColor Green

# Função para converter arquivo para Base64
function ConvertTo-Base64 {
    param([string]$FilePath)
    
    if (-not (Test-Path $FilePath)) {
        Write-Error "Arquivo não encontrado: $FilePath"
        return $null
    }
    
    $content = Get-Content -Path $FilePath -Raw
    $bytes = [System.Text.Encoding]::UTF8.GetBytes($content)
    return [System.Convert]::ToBase64String($bytes)
}

# Gerar novas chaves se solicitado
if ($GenerateNew) {
    Write-Host "Gerando novas chaves Passport..." -ForegroundColor Yellow
    
    # Verificar se o artisan existe
    if (-not (Test-Path "artisan")) {
        Write-Error "Arquivo artisan não encontrado. Execute este script na raiz do projeto Laravel."
        exit 1
    }
    
    # Gerar novas chaves
    php artisan passport:keys --force
    
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Falha ao gerar novas chaves Passport"
        exit 1
    }
    
    Write-Host "Novas chaves geradas com sucesso!" -ForegroundColor Green
}

# Verificar se as chaves existem
if (-not (Test-Path $PrivateKeyPath) -or -not (Test-Path $PublicKeyPath)) {
    Write-Error "Chaves não encontradas. Execute 'php artisan passport:keys' primeiro ou use -GenerateNew"
    exit 1
}

# Converter chaves para Base64
Write-Host "Convertendo chaves para Base64..." -ForegroundColor Yellow

$privateKeyBase64 = ConvertTo-Base64 -FilePath $PrivateKeyPath
$publicKeyBase64 = ConvertTo-Base64 -FilePath $PublicKeyPath

if (-not $privateKeyBase64 -or -not $publicKeyBase64) {
    Write-Error "Falha ao converter chaves para Base64"
    exit 1
}

# Exibir as variáveis de ambiente
Write-Host "`n=== Variáveis de Ambiente para Render ===" -ForegroundColor Cyan
Write-Host "PASSPORT_PRIVATE_KEY=$privateKeyBase64" -ForegroundColor White
Write-Host "PASSPORT_PUBLIC_KEY=$publicKeyBase64" -ForegroundColor White

# Atualizar render.yaml se solicitado
if ($UpdateRender) {
    Write-Host "`nAtualizando render.yaml..." -ForegroundColor Yellow
    
    if (-not (Test-Path "render.yaml")) {
        Write-Error "Arquivo render.yaml não encontrado"
        exit 1
    }
    
    # Fazer backup do render.yaml
    Copy-Item "render.yaml" "render.yaml.backup" -Force
    Write-Host "Backup criado: render.yaml.backup" -ForegroundColor Gray
    
    # Ler o conteúdo atual
    $renderContent = Get-Content "render.yaml" -Raw
    
    # Atualizar ou adicionar as chaves
    if ($renderContent -match "PASSPORT_PRIVATE_KEY") {
        $renderContent = $renderContent -replace "PASSPORT_PRIVATE_KEY:.*", "PASSPORT_PRIVATE_KEY: $privateKeyBase64"
    } else {
        $renderContent += "`n      - key: PASSPORT_PRIVATE_KEY`n        value: $privateKeyBase64"
    }
    
    if ($renderContent -match "PASSPORT_PUBLIC_KEY") {
        $renderContent = $renderContent -replace "PASSPORT_PUBLIC_KEY:.*", "PASSPORT_PUBLIC_KEY: $publicKeyBase64"
    } else {
        $renderContent += "`n      - key: PASSPORT_PUBLIC_KEY`n        value: $publicKeyBase64"
    }
    
    # Salvar o arquivo atualizado
    Set-Content "render.yaml" -Value $renderContent -Encoding UTF8
    Write-Host "render.yaml atualizado com sucesso!" -ForegroundColor Green
}

Write-Host "`n=== Próximos Passos ===" -ForegroundColor Cyan
Write-Host "1. Copie as variáveis de ambiente acima para o painel do Render" -ForegroundColor White
Write-Host "2. Faça o redeploy da aplicação no Render" -ForegroundColor White
Write-Host "3. Verifique se a autenticação Passport está funcionando" -ForegroundColor White

Write-Host "`n=== Script Concluído ===" -ForegroundColor Green