# EcoAlert - Sistema de Autenticação

## Instruções de Uso

### ✅ Sistema de Login Implementado

O sistema agora possui autenticação com dois tipos de usuários:

---

## 📋 Fluxos de Autenticação

### 1. **Usuário Civil (Comum)**
- **Acesso**: Página de acompanhamento de denúncias (`acompanhar.php`)
- **Link de acesso**: `acompanhar.php` → Redireciona para `login.php` se não logado
- **Funcionalidade**: Pode acompanhar o status de suas denúncias

### 2. **Administrador**
- **Acesso**: Painel administrativo (`admin.php`)
- **Link de acesso**: `admin.php` → Redireciona para `login.php` se não logado
- **Funcionalidade**: Pode visualizar, filtrar e gerenciar todas as denúncias

---

## 🔑 Credenciais de Teste

### Administrador
- **Email**: `admin@ecoalert.com`
- **Senha**: `admin123`

---

## 📝 Páginas Principais

| Página | URL | Requer Login | Tipo de Usuário |
|--------|-----|--------------|-----------------|
| Login | `login.php` | Não | Todos |
| Registro | `register.php` | Não | Novo usuário |
| Acompanhamento | `acompanhar.php` | Sim | Civil |
| Administração | `admin.php` | Sim | Admin |

---

## 🛠️ Funcionalidades

### `login.php` - Página de Login
- Autenticação com email e senha
- Validação de credenciais no banco de dados
- Redirecionamento automático conforme tipo de usuário
- Criptografia de senha com bcrypt
- Link para cadastro de novo usuário

### `register.php` - Página de Cadastro
- Formulário de cadastro para novos usuários civis
- Validação de email
- Validação de senha (mínimo 6 caracteres)
- Confirmação de senha
- Verificação de email duplicado

### `acompanhar.php` - Acompanhamento de Denúncias
- Protegido por autenticação
- Permite buscar denúncias
- Botão "Sair" no menu superior
- Acesso apenas para usuários logados

### `admin.php` - Painel Administrativo
- Protegido por autenticação de administrador
- Visualização de todas as denúncias
- Filtros por categoria e status
- Busca avançada
- Estatísticas de denúncias

---

## 🗄️ Alterações no Banco de Dados

### Nova Tabela: `usuarios`
```sql
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(160) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  tipo VARCHAR(20) NOT NULL DEFAULT 'civil',
  nome VARCHAR(120),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Campos**:
- `id`: Identificador único
- `email`: Email do usuário (único)
- `senha`: Senha criptografada (bcrypt)
- `tipo`: 'civil' ou 'admin'
- `nome`: Nome do usuário
- `criado_em`: Data de criação

### Usuário Admin Padrão
Um usuário administrador padrão é criado automaticamente ao executar o script SQL.

---

## 🔐 Segurança

- ✅ Senhas criptografadas com bcrypt
- ✅ Sessões PHP para autenticação
- ✅ Proteção contra acesso não autorizado
- ✅ Validação de email no registro
- ✅ Requisitos de senha mínimos
- ✅ Redirecionamento automático para login

---

## 📱 Funcionalidades Futuras

Possíveis melhorias:
- [ ] Recuperação de senha via email
- [ ] Autenticação de dois fatores
- [ ] Registro de atividades (logs)
- [ ] Gerenciamento de permissões granulares
- [ ] Editar perfil de usuário
- [ ] Histórico de denúncias por usuário

---

## ⚠️ Notas Importantes

1. **Banco de Dados**: Execute o script `banco.sql` para criar as tabelas necessárias
2. **Segurança**: Em produção, altere a senha padrão do admin imediatamente
3. **Configuração**: Verifique `conexao.php` para as credenciais do banco de dados
4. **Sessões**: Certifique-se de que o PHP tem permissão para salvar sessões

---

## 🚀 Como Começar

1. Execute o script SQL: `banco.sql`
2. Acesse `login.php` 
3. Use as credenciais de teste para fazer login
4. Crie uma nova conta civil em `register.php` para testar

---

**Desenvolvido para o EcoAlert - Sistema de Denúncias Ambientais**
