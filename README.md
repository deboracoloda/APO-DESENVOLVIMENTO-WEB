# Mini Sistema de Gestão de Produtos

Projeto prático desenvolvido para a disciplina de **Programação para Internet** (Atividade Prática Orientada - APO), consistindo em uma aplicação web para controle de fornecedores, produtos e cesta de compras.

## Funcionalidades Principais

* **Autenticação e Cadastro de Usuários:** Controle de acesso via sessões PHP (`$_SESSION`) com armazenamento de senhas criptografadas utilizando hash **SHA-256**.
* **Criação Automática do Banco de Dados:** Ao iniciar o sistema pela primeira vez, a classe de conexão executa automaticamente os comandos SQL para criar o banco (`sistema_produtos`) e suas tabelas (`usuarios`, `fornecedores` e `produtos`).
* **Gestão de Fornecedores e Produtos (CRUD):** Cadastro e listagem utilizando **PHP Orientado a Objetos** e **PDO**, mantendo o relacionamento `1:N` entre fornecedores e produtos.
* **Atualização Assíncrona com AJAX:** Edição rápida dos registros diretamente nas tabelas utilizando JavaScript (`fetch API`), atualizando o banco de dados sem recarregar a página.
* **Vitrine com Validação e Cesta de Compras:** Seleção de produtos via *checkbox* com validação no navegador e no servidor, exibindo na cesta a relação de itens escolhidos (1 unidade de cada), a quantidade total e a soma dos valores.

## Tecnologias Utilizadas

* **Backend:** PHP 8+ (Orientado a Objetos e PDO)
* **Banco de Dados:** MySQL
* **Frontend:** HTML5, CSS3, JavaScript (AJAX / Fetch API) e Bootstrap 5

## Estrutura do Projeto

```text
apo_produtos/
├── classes/
│   ├── Fornecedor.php
│   ├── Produto.php
│   └── Usuario.php
├── config/
│   └── Conexao.php
├── includes/
│   └── navbar.php
├── ajax_atualizar.php
├── cesta.php
├── fornecedores.php
├── index.php
├── logout.php
├── produtos.php
├── vitrine.php
└── README.md


Como Executar Localmente
Instale e inicie os serviços Apache e MySQL no XAMPP (ou servidor local equivalente).

Copie a pasta apo_produtos para dentro do diretório htdocs (ex.: C:\xampp\htdocs\apo_produtos).

Acesse no navegador o endereço: http://localhost/apo_produtos/

Crie uma conta na tela inicial para acessar o sistema (o banco de dados e as tabelas serão gerados automaticamente).

