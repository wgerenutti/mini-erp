# Projeto E-Commerce (Laravel 12 + Bootstrap 5)

Loja simples com catálogo de **Produtos**, carrinho em **offcanvas**, checkout com validação de CEP via **ViaCEP**, atualização de estoque e suporte a **Cupons** (valor fixo ou percentual). O carrinho é mantido na sessão e os cupons são aplicados em sessão e gravados no pedido (pivot `pedido_cupons`) ao finalizar.

---

## Tecnologias

- **Laravel 12**
- **PHP 8.2+**
- **Bootstrap 5**
- **MySQL / MariaDB**
- **Fetch API + jQuery**
- **ViaCEP (consulta CEP)**

---

## Pré-requisitos

- PHP, Composer, Node.js/npm
- Banco de dados configurado (MySQL, MariaDB, etc)
- Extensões PHP comuns (`pdo`, `mbstring`, `ctype`, `openssl`, `json`, etc)

---

## Instalação Rápida

```bash
# Clonar o repositório
git clone <repo>
cd <repo>

# Instalar dependências PHP
composer install

# Instalar dependências JS/CSS
npm install
npm run dev   # ou npm run build para produção

# Configurar .env
cp .env.example .env
php artisan key:generate

# Criar banco de dados
php artisan migrate
# Opcional: php artisan db:seed

# Rodar aplicação
php artisan serve
# Acesse http://localhost:8000
```

> Configure `APP_URL` corretamente (usado por alguns fetch/JS).

---

## Como executar

Este projeto pode ser rodado de duas formas:

### 1. Ambiente Local (Laravel)

Siga os passos da seção **Instalação** acima para rodar com `php artisan serve` e acessar via navegador.

### 2. Ambiente Docker

Se preferir usar Docker, basta executar:

```bash
docker-compose up --build
```

Isso irá subir os serviços do app (PHP/Laravel), banco de dados (MariaDB), web server (Nginx) e Mailhog para testes de e-mail.  
Acesse a aplicação em [http://localhost:8000](http://localhost:8000).

> Certifique-se de ajustar variáveis do `.env` conforme necessário para o ambiente Docker.

---

## Migrations Importantes

- **cupons**
  - `id`, `codigo`, `valor_desc`, `pct_desc`, `minimo`
  - `valid_from`, `valid_to`, `validade`
  - `ativo` (boolean), `uso_maximo`, `uso_count`
- **pedido_cupons** (pivot)
  - `pedido_id`, `cupom_id`, `desconto_aplicado` (decimal), timestamps
  - Primária composta (`pedido_id`, `cupom_id`)
- **produtos**, **estoques**, **pedidos**, **pedido_itens**

---

## Arquitetura / Código-chave

### Controllers

- `PedidoController`: gerencia carrinho, produtos, CEP, cupom, finalização.
- `CupomController`: CRUD de cupons e alternância de ativo.

### Models

- `Cupom`: helpers (`isActiveNow()`, `isValidForSubtotal()`, `calculateDiscount()`).
- `Pedido`: relação `itens` e `cupons()` (belongsToMany).
- `Produto`, `Estoque`, `PedidoItem`.

### Views / Partials

- `partials/cart_body.blade.php`: offcanvas do carrinho, campo cupom, subtotal/frete/desconto/total.
- `produtos/index.blade.php`: lista produtos, botão adicionar ao carrinho (AJAX).

### JS

- Fetch + CSRF para:
  - `POST /carrinho/cupom` — aplica cupom via AJAX.
  - `DELETE /carrinho/cupom` — remove cupom via AJAX.
  - `POST /carrinho/{produto}` — adicionar produto.
  - `PATCH /carrinho/{key}` — atualizar quantidade.
- Spinner, offcanvas, re-render parcial com `cart_html`.

---

## Rotas Principais

- `GET /produtos` — catálogo
- `POST /carrinho/{produto}` — adicionar produto
- `PATCH /carrinho/{key}` — atualizar quantidade
- `DELETE /carrinho/{key}` — remover item
- `POST /carrinho/cupom` — aplicar cupom
- `DELETE /carrinho/cupom` — remover cupom
- `POST /pedidos/finalizar` — finalizar pedido
- `resource('cupons')` — CRUD de cupons

---

## Fluxo do Carrinho e Cupom

1. Usuário adiciona produtos → `PedidoController::adicionar()` atualiza sessão via `syncCartSession()`.
2. Usuário aplica cupom → `aplicarCupom()` valida regras, calcula desconto e grava na sessão.
3. `syncCartSession()` **deve preservar** o campo `cupom` na sessão.
4. Ao `finalizar()`:
   - Revalida cupom dentro de DB transaction;
   - Usa `lockForUpdate()` no cupom;
   - Incrementa `uso_count`;
   - Cria `Pedido` + `PedidoItem`s, decrementa estoque;
   - Salva pivot `pedido_cupons` com desconto.

> **Importante:** Se `syncCartSession()` sobrescrever a sessão sem preservar `cupom`, o pivot não será gravado.

---

## Exemplos de Código

**Preservar cupom em `syncCartSession()`:**

```php
$cupomSession = session('carrinho.cupom', null);
// ... calcula subtotal/frete/total/discount ...
$carrinhoToSave = [
    'items' => $items,
    'subtotal' => $subtotal,
    'frete' => $frete,
    'total' => $total,
    'cep' => $cep,
    'endereco' => $endereco,
];
if ($cupomSession) $carrinhoToSave['cupom'] = $cupomSession;
session(['carrinho' => $carrinhoToSave]);
```

**Gravar pivot após criar pedido:**

```php
if ($cupom) {
    $pedido->cupons()->attach($cupom->id, ['desconto_aplicado' => $desconto]);
}
```

**Revalidação do cupom no checkout:**

```php
DB::transaction(function () use ($carrinho) {
    $cupom = Cupom::lockForUpdate()->find($carrinho['cupom']['id']);
    // validações...
    $cupom->increment('uso_count');
    // criar pedido...
});
```

---

## Troubleshooting

- **Cupom aparece no UI, mas `pedido_cupons` fica vazio**
  - Solução: atualizar `syncCartSession()` para copiar `cupom` ao salvar.
- **`uso_count` não incrementa corretamente**
  - Use `lockForUpdate()` na transaction.
- **CEP inválido no finalizar**
  - Verifique conexão com ViaCEP e trate erros.
- **Erro 419 em AJAX**
  - Confirme token CSRF nas headers.

---

## Testes Sugeridos

- Aplicar cupom inexistente
- Aplicar cupom expirado
- Aplicar cupom com mínimo (abaixo/acima)
- Atualizar qty com cupom aplicado
- Finalizar com cupom ativo e checar `pedido_cupons`
- Testar lock com `uso_maximo=1`

---

## Como Contribuir / Extensões

- Permitir múltiplos cupons por pedido
- Restrições avançadas (cliente, produto, categoria)
- Logs/telemetria para uso de cupom
- Testes automatizados (Feature tests)

---
