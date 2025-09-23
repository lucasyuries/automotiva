# Projeto Automotiva 🚗

### Um E-commerce Completo para Estética Automotiva

**Automotiva** é um projeto acadêmico que simula um site de e-commerce completo para uma empresa fictícia de estética automotiva. O sistema foi desenvolvido com foco em funcionalidades essenciais de uma loja virtual, utilizando PHP puro para o backend e uma abordagem de componentização e organização de código para garantir a manutenibilidade e a segurança.

O site oferece uma experiência de usuário fluida, desde o cadastro e login até a finalização da compra com um fluxo de pagamento simulado via PIX e o acompanhamento dos pedidos realizados.

---

### ✨ Funcionalidades Implementadas

O projeto conta com as seguintes funcionalidades:

* **Autenticação de Usuário:**
    * Sistema de registro de novos usuários com verificação de e-mail existente.
    * Hashing de senhas com `password_hash` para armazenamento seguro.
    * Página de login com validação de credenciais e gerenciamento de sessão.
    * Logout seguro, destruindo a sessão do usuário.

* **Catálogo de Produtos:**
    * Listagem de produtos e serviços em carrosséis interativos na página inicial.
    * Página de carrinho que exibe todos os produtos disponíveis para compra.
    * Modal de visualização para ampliar imagens dos produtos.

* **Carrinho de Compras Dinâmico:**
    * Adição, remoção e atualização de quantidade de itens no carrinho.
    * Interface totalmente dinâmica utilizando **AJAX**, que atualiza o carrinho sem a necessidade de recarregar a página.
    * Cálculo de subtotal e total em tempo real.

* **Fluxo de Checkout:**
    * Página de checkout protegida, acessível apenas para usuários logados.
    * Preenchimento automático dos dados do usuário (nome, e-mail).
    * Simulação de pagamento via **PIX**, com exibição de QR Code e chave "copia e cola".
    * O pedido é salvo no banco de dados de forma transacional, garantindo a integridade dos dados.

* **Histórico de Pedidos:**
    * Página "Meus Pedidos" para que o usuário logado possa visualizar todas as suas compras anteriores.
    * Exibição detalhada de cada pedido, incluindo data, status, valor total e a lista de produtos comprados em um layout de grid.

* **Formulário de Contato:**
    * Formulário para envio de mensagens, que são salvas no banco de dados para futura consulta por um administrador.
    * Feedback instantâneo para o usuário após o envio da mensagem.

---

### 🛠️ Tecnologias Utilizadas

#### **Frontend:**
* **HTML5** (Estrutura Semântica)
* **CSS3** (Design Responsivo com Flexbox e Grid, Animações)
* **Google Fonts** (Poppins)
* **JavaScript** (Manipulação do DOM, Requisições Assíncronas com AJAX)

#### **Backend:**
* **PHP 8+** (Lógica de Servidor)
* **MySQL / MariaDB** (Banco de Dados Relacional)
* **PDO** (Conexão segura com o banco de dados)

#### **Ferramentas:**
* **Git & GitHub** (Controle de Versão)
* **Composer** (Gerenciador de Dependências PHP)
* **XAMPP** (Ambiente de Servidor Local)

---

### 🚀 Como Executar o Projeto

Para rodar este projeto localmente, você precisará de um ambiente de servidor como **XAMPP**. As instruções abaixo são para o XAMPP.

**1. Clone o Repositório**
Abra o terminal na pasta `htdocs` do seu XAMPP e clone o projeto.
*Exemplo no Windows:* `C:\xampp\htdocs\`

```bash
git clone [https://github.com/lucasyuries/automotiva.git](https://github.com/lucasyuries/automotiva.git)



/automotiva
├── vendor/               # Dependências do Composer (gerenciado automaticamente)
├── .env                  # (Local) Configurações do ambiente (ignorado pelo Git)
├── .env.example          # Arquivo de exemplo para as variáveis de ambiente
├── .gitignore            # Arquivos e pastas a serem ignorados pelo Git
├── atualizar_carrinho.php# Endpoint AJAX para o carrinho
├── carrinho.php          # Página do carrinho de compras
├── checkout.php          # Página de finalização da compra
├── config.php            # Carrega .env e configura a conexão com o BD
├── composer.json         # Define as dependências do projeto
├── composer.lock         # Grava as versões exatas das dependências
├── database.sql          # Script de criação do banco de dados
├── enviar_contato.php    # Backend do formulário de contato
├── finalizar_pedido.php  # Backend do checkout
├── index.php             # Página inicial
├── login.php             # Página de login
├── logout.php            # Script de logout
├── meus_pedidos.php      # Página de histórico de pedidos do usuário
├── pedido_sucesso.php    # Página de confirmação de pedido
├── README.md             # Este arquivo
├── registro.php          # Página de registro de usuário
├── script.js             # Lógica JavaScript do frontend
└── styles.css            # Folha de estilos principal do site
