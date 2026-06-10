# 🌿 EcoAlert - Sistema de Denúncias Ambientais

O **EcoAlert** é uma plataforma web desenvolvida para facilitar o registro, gerenciamento e o acompanhamento de denúncias de infrações ambientais. O sistema conecta cidadãos preocupados com o meio ambiente a um painel administrativo que permite a triagem e o acompanhamento de cada caso.

---

## ✨ Funcionalidades

### 👤 Para o Cidadão (Civil)
* **Cadastro e Login Seguros**: Criação de conta para acompanhar o andamento de denúncias.
* **Registro de Denúncias**: Formulário com captura de dados, categoria da infração, descrição e localização (latitude e longitude).
* **Acompanhamento**: Área restrita para visualizar o status de todas as denúncias enviadas pelo próprio usuário.

### 👮 Para o Administrador
* **Dashboard Central**: Visão global de todas as denúncias registradas na plataforma.
* **Gestão de Denúncias**: Filtros inteligentes por categoria, status ou busca livre.
* **Estatísticas**: Informações consolidadas sobre o volume de denúncias.
* **Gestão de Usuários**: Possibilidade de cadastrar novos administradores no sistema.

### 🔒 Segurança Implementada
* Criptografia de senhas (hash via **Bcrypt**).
* Proteção contra acessos indevidos com autenticação baseada em **Sessões PHP**.
* Proteção ativa contra *SQL Injection* utilizando **Prepared Statements**.

---

## 🚀 Tecnologias Utilizadas

* **Frontend**: HTML5, CSS3, JavaScript puro
* **Backend**: PHP 8.1
* **Banco de Dados**: MySQL 8.0
* **Servidor**: Apache (via XAMPP)

---

## ⚙️ Pré-requisitos

Para rodar este projeto em sua máquina local, você precisará ter instalado:
* [XAMPP](https://www.apachefriends.org/pt_br/index.html) (ou outro ambiente como WAMP/MAMP que ofereça Apache e MySQL).

---

## 🛠️ Como Instalar e Rodar Localmente

Siga o passo a passo abaixo para rodar o EcoAlert:

1. **Instale e inicie o XAMPP**
   * Abra o painel de controle do XAMPP e inicie os módulos **Apache** e **MySQL**.

2. **Copie os arquivos para o servidor web**
   * Clone ou baixe este repositório e cole a pasta do projeto dentro do diretório `C:\xampp\htdocs\` (ou equivalente em seu sistema operativo).

3. **Configure o Banco de Dados**
   * Acesse o phpMyAdmin pelo navegador: `http://localhost/phpmyadmin/`.
   * Clique na aba "Importar" e envie o arquivo `banco.sql` (disponível na raiz do projeto). O script criará o banco `ecoalert` automaticamente.

4. **Acesse o sistema**
   * Abra o navegador e acesse: `http://localhost/ecoalert/` (substitua "ecoalert" caso tenha renomeado a pasta).
   * *Dica: Se quiser apenas testar a navegação sem precisar ligar o servidor ou banco de dados, abra o arquivo `demo-interativa.html` diretamente no seu navegador.*

---

*Para o acesso civil, basta utilizar a página de Registro (`register.php`) e criar sua própria conta.*

---

## 📄 Autoria e Documentação

* O projeto possui documentação extra em formato `.html` e `.md` (`RESUMO_COMPLETO.md`, `AUTENTICACAO.md` e o guia interativo `GUIA_COMPLETO.html`).
* **Data da versão original:** Junho de 2026.
