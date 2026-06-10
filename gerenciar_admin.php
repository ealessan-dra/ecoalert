<?php
/**
 * Script auxiliar para gerenciar administrador
 * Use este arquivo para criar ou atualizar senhas de admin
 * 
 * ⚠️ IMPORTANTE: Delete este arquivo após usar em produção!
 */

require_once "conexao.php";

// Mensagens
$mensagem = "";
$tipo_mensagem = "";

// Processar formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["acao"])) {
        if ($_POST["acao"] === "criar_admin") {
            $email = trim($_POST["email_admin"] ?? "");
            $nome = trim($_POST["nome_admin"] ?? "");
            $senha = trim($_POST["senha_admin"] ?? "");
            
            if (empty($email) || empty($senha) || empty($nome)) {
                $mensagem = "Todos os campos são obrigatórios.";
                $tipo_mensagem = "erro";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensagem = "Email inválido.";
                $tipo_mensagem = "erro";
            } elseif (strlen($senha) < 6) {
                $mensagem = "A senha deve ter no mínimo 6 caracteres.";
                $tipo_mensagem = "erro";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
                
                $sql = "INSERT INTO usuarios (email, senha, tipo, nome) VALUES (?, ?, 'admin', ?)
                        ON DUPLICATE KEY UPDATE senha = ?, nome = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("sssss", $email, $senha_hash, $nome, $senha_hash, $nome);
                
                if ($stmt->execute()) {
                    $mensagem = "✅ Administrador criado/atualizado com sucesso!";
                    $tipo_mensagem = "sucesso";
                } else {
                    $mensagem = "❌ Erro ao criar administrador: " . $conexao->error;
                    $tipo_mensagem = "erro";
                }
            }
        }
    }
}

// Listar administradores
$administradores = [];
$sql = "SELECT id, email, nome, criado_em FROM usuarios WHERE tipo = 'admin' ORDER BY criado_em DESC";
$resultado = $conexao->query($sql);
if ($resultado) {
    while ($linha = $resultado->fetch_assoc()) {
        $administradores[] = $linha;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoAlert | Gerenciar Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c5f2d;
            margin-bottom: 10px;
            text-align: center;
        }

        .aviso {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #856404;
            font-size: 14px;
        }

        .aviso strong {
            display: block;
            margin-bottom: 5px;
        }

        .formulario {
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2c5f2d;
            box-shadow: 0 0 5px rgba(44, 95, 45, 0.2);
        }

        .botao {
            width: 100%;
            padding: 12px;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .botao:hover {
            background: #1f4620;
        }

        .mensagem {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .mensagem.sucesso {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }

        .mensagem.erro {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .lista-admins {
            margin-top: 30px;
        }

        .lista-admins h2 {
            color: #2c5f2d;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .admin-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            border-left: 4px solid #2c5f2d;
        }

        .admin-item strong {
            color: #333;
        }

        .admin-item span {
            color: #666;
            font-size: 14px;
        }

        .vazio {
            text-align: center;
            color: #999;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Gerenciar Administrador</h1>

        <div class="aviso">
            <strong>⚠️ Atenção:</strong>
            Este é um script administrativo. Delete-o após usar em produção por questões de segurança!
        </div>

        <?php if ($mensagem): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form class="formulario" method="POST">
            <input type="hidden" name="acao" value="criar_admin">

            <div class="form-group">
                <label for="nome_admin">Nome do Administrador:</label>
                <input type="text" id="nome_admin" name="nome_admin" placeholder="Ex: João Admin" required>
            </div>

            <div class="form-group">
                <label for="email_admin">Email:</label>
                <input type="email" id="email_admin" name="email_admin" placeholder="Ex: admin@ecoalert.com" required>
            </div>

            <div class="form-group">
                <label for="senha_admin">Senha (mínimo 6 caracteres):</label>
                <input type="password" id="senha_admin" name="senha_admin" placeholder="Digite uma senha forte" required>
            </div>

            <button class="botao" type="submit">Criar/Atualizar Administrador</button>
        </form>

        <?php if (!empty($administradores)): ?>
            <div class="lista-admins">
                <h2>👤 Administradores Cadastrados</h2>
                <?php foreach ($administradores as $admin): ?>
                    <div class="admin-item">
                        <strong><?php echo htmlspecialchars($admin["nome"]); ?></strong>
                        <br>
                        <span>Email: <?php echo htmlspecialchars($admin["email"]); ?></span>
                        <br>
                        <span>Criado em: <?php echo htmlspecialchars($admin["criado_em"]); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="lista-admins">
                <div class="vazio">Nenhum administrador cadastrado ainda.</div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
