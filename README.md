# Automotiva - Landing Page de Estética Automotiva

Este é o repositório de uma landing page para uma empresa de estética automotiva fictícia chamada "Automotiva". O projeto foi desenvolvido para ser moderno, responsivo e dinâmico, com uma seção de produtos gerenciada por um banco de dados.

## ✨ Features

- **Design Responsivo**: Totalmente adaptável a desktops, tablets e smartphones (Mobile-First).
- **Seções Completas**:
    - Header com navegação fixa.
    - Seção Hero com chamada para ação (CTA).
    - Apresentação de Serviços.
    - Listagem de Produtos dinâmica, carregada via PHP e MySQL.
    - Seção "Sobre Nós".
    - Depoimentos de clientes.
    - Formulário de Contato.
    - Footer com informações e links sociais.
- **Interatividade**: Menu de navegação funcional em dispositivos móveis e animações sutis com CSS.
- **Backend Simples**: Utiliza PHP para conectar a um banco de dados MySQL e exibir produtos.

## 🛠️ Tecnologias Utilizadas

- **Frontend**:
    - HTML5 (Estrutura Semântica)
    - CSS3 (Flexbox, Grid, Animações)
    - Google Fonts (Poppins)
    - JavaScript (Manipulação do DOM)
- **Backend**:
    - PHP
    - MySQL (ou MariaDB)

## 🚀 Como Executar o Projeto

Para rodar este projeto localmente, você precisará de um ambiente de servidor como XAMPP, WAMP ou MAMP. As instruções abaixo são para o **XAMPP**.

1.  **Clone o Repositório**
    ```bash
    git clone https://github.com/seu-usuario/automotiva.git
    ```

2.  **Mova a Pasta do Projeto**
    Mova a pasta `automotiva` para o diretório `htdocs` dentro da sua instalação do XAMPP.
    - Exemplo no Windows: `C:\xampp\htdocs\automotiva`

3.  **Inicie o XAMPP**
    Abra o painel de controle do XAMPP e inicie os módulos **Apache** e **MySQL**.

4.  **Crie e Popule o Banco de Dados**
    - Abra seu navegador e acesse o **phpMyAdmin** em `http://localhost/phpmyadmin`.
    - Clique na aba **Importar**.
    - Clique em "Escolher arquivo" e selecione o arquivo `database.sql` que está na raiz do projeto.
    - Deixe as configurações padrão e clique em **Executar** no final da página.
    - Isso criará o banco de dados `automotiva_db`, a tabela `produtos` e inserirá os dados de exemplo.

5.  **Acesse o Site**
    Abra seu navegador e acesse `http://localhost/automotiva/`.

Pronto! O site deverá estar funcionando e exibindo os produtos a partir do banco de dados.

## 📂 Estrutura de Arquivos

```
/automotiva
├── 📄 config.php           # Configurações de conexão com o banco de dados
├── 📄 database.sql         # Script para criação do banco de dados e tabelas
├── 📄 index.php            # Arquivo principal da landing page (HTML + PHP)
├── 📄 script.js            # Código JavaScript para interatividade
├── 📄 styles.css           # Folha de estilos
└── 📄 README.md            # Este arquivo
```

---
