<?php
session_start();
require_once "conexao.php";

$erro = "";
$sucesso = "";

// Se já está logado, redirecionar
if (isset($_SESSION["usuario_id"])) {
    if ($_SESSION["tipo_usuario"] === "admin") {
        header("Location: admin.php");
    } else {
        header("Location: acompanhar.php");
    }
    exit;
}

// Processar registro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = trim($_POST["senha"] ?? "");
    $confirmar_senha = trim($_POST["confirmar_senha"] ?? "");
    
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    } else {
        // Verificar se o email já existe
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $erro = "Este email já está cadastrado.";
        } else {
            // Criptografar a senha
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
            
            // Inserir novo usuário
            $sql = "INSERT INTO usuarios (email, senha, tipo, nome) VALUES (?, ?, 'civil', ?)";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sss", $email, $senha_hash, $nome);
            
            if ($stmt->execute()) {
                $sucesso = "Cadastro realizado com sucesso! Faça o login para continuar.";
                // Limpar os campos
                $nome = "";
                $email = "";
                $senha = "";
                $confirmar_senha = "";
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoAlert | Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container-registro {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px);
            padding: 20px;
        }

        .formulario-registro {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .formulario-registro h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c5f2d;
            font-size: 28px;
        }

        .formulario-registro .form-group {
            margin-bottom: 20px;
        }

        .formulario-registro label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .formulario-registro input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .formulario-registro input:focus {
            outline: none;
            border-color: #2c5f2d;
            box-shadow: 0 0 5px rgba(44, 95, 45, 0.2);
        }

        .formulario-registro button {
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

        .formulario-registro button:hover {
            background: #1f4620;
        }

        .formulario-registro button:active {
            transform: scale(0.98);
        }

        .mensagem-erro {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }

        .mensagem-sucesso {
            background: #efe;
            color: #3c3;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #3c3;
        }

        .link-volta {
            text-align: center;
            margin-top: 20px;
        }

        .link-volta a {
            color: #2c5f2d;
            text-decoration: none;
            font-weight: 600;
            margin-right: 15px;
        }

        .link-volta a:hover {
            text-decoration: underline;
        }

        .requisitos-senha {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 12px;
            color: #666;
            border-left: 4px solid #2c5f2d;
        }

        .requisitos-senha p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <header class="topo">
        <a class="logo" href="index.html">EcoAlert</a>
        <nav class="menu" aria-label="Menu principal">
            <a href="index.html">Início</a>
            <a href="index.html#denuncia">Fazer denúncia</a>
            <a href="acompanhar.php">Acompanhar denúncia</a>
            <a href="orientacoes.html">Orientações</a>
            <a href="contato.html">Contato</a>
        </nav>
    </header>

    <div class="container-registro">
        <form class="formulario-registro" method="POST">
            <h1>Cadastro</h1>

            <?php if ($erro): ?>
                <div class="mensagem-erro"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="mensagem-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome ?? ""); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ""); ?>" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>

            <button type="submit">Criar Conta</button>

            <div class="requisitos-senha">
                <p><strong>Requisitos de senha:</strong></p>
                <p>• Mínimo 6 caracteres</p>
            </div>

            <div class="link-volta">
                <a href="login.php">Já tem conta? Faça o login</a>
                <a href="index.html">Voltar</a>
            </div>
        </form>
    </div>
</body>
</html>
