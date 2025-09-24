# README feat/data-export-functionality

Este documento descreve as implementações realizadas na branch feat/data-export-functionality do Sprint 5.

## Objetivo

Implementar funcionalidade de exportação de dados de relatórios de gastos em formato CSV, permitindo que usuários façam download de seus dados para análise externa ou backup.

## Passos realizados

1. Implementação do endpoint de exportação CSV
	- Arquivo: app/Http/Controllers/Api/ReportController.php
	- Método implementado:
	  - exportMyExpenses(): GET /api/v1/reports/my-expenses/export (exportação CSV dos gastos)

2. Configuração da rota de exportação
	- Arquivo: routes/api.php
	- Rota protegida: /api/v1/reports/my-expenses/export (middleware auth:api)
	- Suporte aos mesmos filtros do relatório (status via query parameter)

3. Funcionalidades da exportação CSV
	- Geração de arquivo CSV com dados das subscriptions do usuário
	- Colunas exportadas:
	  - Service Name (nome do serviço)
	  - Plan (plano da subscription)
	  - Price (valor)
	  - Currency (moeda)
	  - Status (ativo/cancelado)
	  - Next Billing Date (próxima data de cobrança)
	- Nomenclatura automática do arquivo: expenses_YYYY_MM_DD.csv
	- Headers HTTP apropriados para download de arquivo
	- Encoding UTF-8 para suporte a caracteres especiais

4. Filtros e segurança
	- Aplicação dos mesmos filtros disponíveis no relatório
	- Filtro por status (?status=active|cancelled)
	- Isolamento de dados por usuário (cada usuário exporta apenas suas próprias subscriptions)
	- Autenticação obrigatória via middleware auth:api

5. Implementação técnica
	- Geração de CSV nativo usando Response do Laravel
	- Headers Content-Type: text/csv; charset=UTF-8
	- Header Content-Disposition para forçar download
	- Lógica de consulta reutilizada do método de relatório existente

6. Testes automatizados abrangentes
	- Arquivo: tests/Feature/ReportApiEndpointsTest.php
	- 4 testes específicos para exportação:
	  - Exportação completa de dados em CSV
	  - Exportação com filtros aplicados
	  - Proteção contra acesso não autenticado
	  - Isolamento de dados entre usuários

## Estrutura do arquivo CSV gerado

```csv
Service Name,Plan,Price,Currency,Status,Next Billing Date
Netflix,Premium,15.99,USD,active,2024-12-01
Spotify,Individual,9.99,USD,cancelled,2024-11-15
```

## Endpoints implementados

### GET /api/v1/reports/my-expenses/export
- **Autenticação**: Obrigatória (Bearer Token)
- **Parâmetros opcionais**:
  - `status`: Filtrar por status (active|cancelled)
- **Resposta**: Arquivo CSV para download
- **Headers de resposta**:
  - Content-Type: text/csv; charset=UTF-8
  - Content-Disposition: attachment; filename="expenses_YYYY_MM_DD.csv"

## Validação e qualidade

- Testes automatizados cobrindo todos os cenários de uso
- Validação de headers HTTP corretos
- Verificação de conteúdo CSV gerado
- Teste de isolamento de dados entre usuários
- Validação de filtros aplicados na exportação