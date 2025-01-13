# e_CommerceCMS

Um sistema de e-commerce completo com gerenciamento de produtos, carrinho de compras, checkout com integração ao PayPal e interface de administração.

## Funcionalidades

- **Gerenciamento de Produtos**: Adicionar, editar e excluir produtos no painel de administração.
- **Carrinho de Compras**: Adicionar produtos ao carrinho, editar quantidades e remover itens.
- **Pagamento com PayPal**: Integração para pagamentos seguros.
- **Conta do Cliente**: Gerencie pedidos, edite informações pessoais e veja detalhes de compras.
- **Administração**: Controle pedidos, usuários e estoque.
  
## Tecnologias Utilizadas

- **Backend**: PHP e MySQL
- **Frontend**: HTML, CSS e JavaScript (com Bootstrap)
- **Integração de Pagamento**: PayPal API
- **Controle de Versão**: Git e GitHub

## Configuração do Projeto

### Pré-requisitos

- PHP (8.0 ou superior)
- Servidor Apache ou Nginx
- MySQL
- Composer

### Passos para Configuração

1. Clone o repositório:
   ```bash
   git clone https://github.com/marcosdeoliv/e_commerceCMS.git
   cd e_commerceCMS

## Configuração do Banco de Dados

1. Crie um banco de dados no MySQL chamado `e_commerce`.
2. Localize o arquivo SQL no repositório:

3. Importe o script SQL para o banco de dados:
- Use uma interface gráfica como o **phpMyAdmin** ou:
  ```bash
  mysql -u [seu_usuario] -p e_commerce < database/cms_db.sql
  ```
4. Atualize as credenciais no arquivo de conexão:
```php
// server/connection.php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'e_commerce');

### Estrutura do Banco de Dados

#### Tabelas Principais

- **users**: Registra as informações dos clientes.
  - `user_id` (PK): ID único do cliente.
  - `user_name`: Nome do cliente.
  - `user_email`: E-mail do cliente.
  - `user_password`: Senha criptografada.
  
- **products**: Lista dos produtos disponíveis.
  - `product_id` (PK): ID único do produto.
  - `product_name`: Nome do produto.
  - `product_price`: Preço do produto.
  - `product_image`: Caminho para a imagem do produto.

- **orders**: Informações dos pedidos.
  - `order_id` (PK): ID único do pedido.
  - `user_id` (FK): Cliente que fez o pedido.
  - `order_cost`: Custo total.
  - `order_status`: Status do pedido.

- **order_items**: Itens dentro de um pedido.
  - `item_id` (PK): ID único do item.
  - `order_id` (FK): Pedido relacionado.
  - `product_id` (FK): Produto relacionado.
  - `qnt`: Quantidade do item no pedido.

Contribuições são bem vindas!
---
   Este projeto é licenciado sob a Licença MIT "https://mit-license.org/".
=======
# e_commerceCMS
projeto inicial em PHP de um sistema e_Commerce
