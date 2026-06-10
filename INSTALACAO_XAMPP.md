# 📦 Guia de Instalação: XAMPP, PHP e MySQL

## ✅ O que será instalado:

- **Apache 2.4** - Servidor web
- **PHP 8.1** - Linguagem de programação (servidor)
- **MySQL 8.0** - Banco de dados
- **phpMyAdmin** - Interface para gerenciar banco de dados

---

## 📥 Opção 1: Instalação Automática (Recomendado)

### Passo 1: Aguardar o download do XAMPP
O sistema está baixando o XAMPP automaticamente via `winget`. Aguarde até 100% do download.

### Passo 2: Instalar
Quando o download terminar, o instalador será executado automaticamente. Siga as instruções na tela.

**Configurações recomendadas:**
- Instalação em: `C:\xampp` (padrão)
- Componentes: Todos (Apache, MySQL, PHP, phpMyAdmin)

### Passo 3: Concluir instalação
Quando o instalador perguntar, clique em "Finish" para completar a instalação.

---

## 📥 Opção 2: Download Manual

Se a instalação automática não funcionar:

### Passo 1: Download
Acesse: https://www.apachefriends.org/download.html

Baixe a versão: **XAMPP Windows 8.1.x (PHP 8.1)**

### Passo 2: Instalar
1. Execute o arquivo `.exe` baixado
2. Clique em "Próximo" até o final
3. Quando perguntar qual pasta, deixe como `C:\xampp`
4. Complete a instalação

### Passo 3: Iniciar XAMPP
1. Abra o **XAMPP Control Panel**
2. Clique em "Start" para Apache
3. Clique em "Start" para MySQL

---

## ✨ Depois de Instalado

### 1️⃣ Verificar PHP
Abra `cmd` e digite:
```bash
php -v
```

Deve mostrar: `PHP 8.1.x`

### 2️⃣ Verificar MySQL
```bash
mysql -u root
```

Deve conectar sem erro

### 3️⃣ Copiar arquivos do projeto
Copie a pasta `Eduarda Santiago` para:
```
C:\xampp\htdocs\
```

### 4️⃣ Acessar no navegador

| Serviço | URL |
|---------|-----|
| Dashboard XAMPP | http://localhost |
| Seu Projeto | http://localhost/Eduarda%20Santiago/ |
| phpMyAdmin | http://localhost/phpmyadmin/ |
| Login | http://localhost/Eduarda%20Santiago/login.php |

### 5️⃣ Configurar banco de dados

1. Abra phpMyAdmin: http://localhost/phpmyadmin/
2. Coloque o arquivo `banco.sql` (que criamos) no navegador
3. Clique "Executar" para criar as tabelas
4. Pronto! Banco de dados configurado.

---

## 🔧 Iniciar/Parar serviços

### Via XAMPP Control Panel:
1. Abra a pasta: `C:\xampp\xampp-control.exe`
2. Clique em "Start" ao lado de Apache e MySQL

### Via Linha de Comando:
```powershell
# Iniciar Apache
net start Apache2.4

# Iniciar MySQL
net start MySQL80

# Parar Apache
net stop Apache2.4

# Parar MySQL
net stop MySQL80
```

---

## 🌐 Estrutura de arquivos

```
C:\xampp\
├── apache\          (Servidor web)
├── mysql\           (Banco de dados)
├── php\             (Interpretador PHP)
├── phpmyadmin\      (Interface do banco)
└── htdocs\          ← SEUS PROJETOS VÃO AQUI
    └── Eduarda Santiago\
        ├── login.php
        ├── register.php
        ├── admin.php
        ├── acompanhar.php
        ├── banco.sql
        └── ...
```

---

## 🧪 Testar Sistema Completo

### 1. Iniciar Apache e MySQL
2. Criar banco de dados com `banco.sql`
3. Acessar: http://localhost/Eduarda%20Santiago/login.php
4. Login com: `admin@ecoalert.com` / `admin123`

---

## ❌ Troubleshooting

### Porta 80 já em uso?
Se Apache não inicia na porta 80:
```powershell
netstat -ano | findstr :80
```
Feche o programa que está usando a porta.

### MySQL não conecta?
Verifique se MySQL está rodando:
```powershell
net start MySQL80
```

### PHP não reconhecido?
Adicione PHP ao PATH:
1. Variáveis de ambiente Windows
2. PATH → Adicione: `C:\xampp\php`
3. Reinicie cmd

### Arquivo não encontrado (404)?
- Verificar se arquivo está em `C:\xampp\htdocs\`
- Verificar URL: http://localhost/pasta/arquivo.php
- Reiniciar Apache

---

## 📞 Suporte

Se encontrar problemas:
1. Verifique os logs em `C:\xampp\apache\logs\`
2. Acesse http://localhost para testar Apache
3. Acesse http://localhost/phpmyadmin para testar MySQL

---

**Instalação completa! Seu sistema está pronto para usar. 🚀**
