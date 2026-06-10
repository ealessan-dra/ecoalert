<?php
session_start();
require_once "conexao.php";

$erro = "";
$sucesso = "";

// Processar logout
if (isset($_GET["sair"])) {
    session_destroy();
    header("Location: index.html");
    exit;
}

// Se já está logado, redirecionar
if (isset($_SESSION["usuario_id"])) {
    if ($_SESSION["tipo_usuario"] === "admin") {
        header("Location: admin.php");
    } else {
        header("Location: acompanhar.php");
    }
    exit;
}

// Processar login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = trim($_POST["senha"] ?? "");
    
    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $sql = "SELECT id, email, senha, tipo, nome FROM usuarios WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificar senha
            if (password_verify($senha, $usuario["senha"])) {
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_email"] = $usuario["email"];
                $_SESSION["tipo_usuario"] = $usuario["tipo"];
                $_SESSION["usuario_nome"] = $usuario["nome"];
                
                if ($usuario["tipo"] === "admin") {
                    $_SESSION["admin_logado"] = true;
                    header("Location: admin.php");
                } else {
                    header("Location: acompanhar.php");
                }
                exit;
            } else {
                $erro = "Email ou senha incorretos.";
            }
        } else {
            $erro = "Email ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoAlert | Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container-login {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px);
            padding: 20px;
        }

        .formulario-login {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .formulario-login h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c5f2d;
            font-size: 28px;
        }

        .formulario-login .form-group {
            margin-bottom: 20px;
        }

        .formulario-login label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .formulario-login input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .formulario-login input:focus {
            outline: none;
            border-color: #2c5f2d;
            box-shadow: 0 0 5px rgba(44, 95, 45, 0.2);
        }

        .formulario-login button {
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

        .formulario-login button:hover {
            background: #1f4620;
        }

        .formulario-login button:active {
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
        }

        .link-volta a:hover {
            text-decoration: underline;
        }

        .info-login {
            background: #f0f8f1;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 12px;
            color: #555;
            border-left: 4px solid #2c5f2d;
        }

        .info-login p {
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

    <div class="container-login">
        <form class="formulario-login" method="POST">
            <h1>Login</h1>

            <?php if ($erro): ?>
                <div class="mensagem-erro"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="mensagem-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit">Entrar</button>

            <div class="link-volta">
                <a href="index.html">Voltar para a página inicial</a>
                <span style="margin: 0 10px; color: #999;">|</span>
                <a href="register.php">Criar conta</a>
            </div>


        </form>
    </div>
</body>
</html>
