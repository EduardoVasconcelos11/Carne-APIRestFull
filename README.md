
# API RESTful de Geração de Carnê

Esta API foi desenvolvida em Laravel para gerar e apresentar as parcelas de um carnê.

## Requisitos

- PHP 8.1 ou superior
- Composer
- SQLite (ou outro banco de dados suportado pelo Laravel)

## Instalação

1. Clone o repositório:
    ```bash
    git clone <url-do-repositorio>
    cd <nome-do-repositorio>
    ```

2. Instale as dependências do projeto:
    ```bash
    composer install
    ```

3. Crie o arquivo `.env` com base no `.env.example` e configure o banco de dados SQLite:
    ```bash
    cp .env.example .env
    ```
    No arquivo `.env`, configure a variável `DB_CONNECTION` para `sqlite` e defina o caminho para o banco de dados SQLite:
    ```
    DB_CONNECTION=sqlite
    DB_DATABASE=/caminho/para/seu/database.sqlite
    ```

4. Crie o arquivo de banco de dados SQLite:
    ```bash
    touch /caminho/para/seu/database.sqlite
    ```

5. Execute as migrações para criar as tabelas no banco de dados:
    ```bash
    php artisan migrate
    ```

6. Inicie o servidor de desenvolvimento:
    ```bash
    php artisan serve
    ```

## Endpoints da API

### 1. Criar Carnê

**Endpoint**: `POST /api/carne`

**Descrição**: Cria um novo carnê e retorna as parcelas geradas.

**Parâmetros**:

- `valor_total` (float, obrigatório): O valor total do carnê.
- `qtd_parcelas` (int, obrigatório): A quantidade de parcelas.
- `data_primeiro_vencimento` (string, obrigatório, formato YYYY-MM-DD): A data do primeiro vencimento.
- `periodicidade` (string, obrigatório, valores possíveis: "mensal", "semanal"): A periodicidade das parcelas.
- `valor_entrada` (float, opcional): O valor da entrada.

**Exemplo de Requisição**:
```json
{
  "valor_total": 100.00,
  "qtd_parcelas": 12,
  "data_primeiro_vencimento": "2024-08-01",
  "periodicidade": "mensal"
}
```

**Exemplo de Resposta**:
```json
{
  "total": 100.00,
  "valor_entrada": 0,
  "parcelas": [
    {
      "data_vencimento": "2024-08-01",
      "valor": 8.33,
      "numero": 1
    },
    ...
  ]
}
```

### 2. Recuperar Parcelas de um Carnê

**Endpoint**: `GET /api/carne/{id}/parcelas`

**Descrição**: Retorna as parcelas de um carnê existente.

**Parâmetros**:

- `id` (int, obrigatório): O identificador do carnê.

**Exemplo de Resposta**:
```json
[
  {
    "data_vencimento": "2024-08-01",
    "valor": 8.33,
    "numero": 1
  },
  ...
]
```

## Tratamento de Erros

A API retorna respostas JSON para erros de validação e outras exceções.

**Exemplo de Erro de Validação**:
```json
{
  "errors": {
    "valor_total": ["O campo valor total é obrigatório."]
  }
}
```

## Licença

Este projeto está licenciado sob a licença MIT.
