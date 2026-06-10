<?php
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$categoria = trim($_POST["categoria"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$endereco = trim($_POST["endereco"] ?? "");
$latitude = trim($_POST["latitude"] ?? "");
$longitude = trim($_POST["longitude"] ?? "");

if (
    $nome === "" ||
    $email === "" ||
    $categoria === "" ||
    $descricao === "" ||
    $endereco === "" ||
    $latitude === "" ||
    $longitude === ""
) {
    die("Preencha todos os campos obrigatórios.");
}

$sql = "INSERT INTO denuncias (nome, email, categoria, descricao, endereco, latitude, longitude)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar cadastro: " . $conexao->error);
}

$stmt->bind_param("sssssss", $nome, $email, $categoria, $descricao, $endereco, $latitude, $longitude);

if ($stmt->execute()) {
    echo "<h1>Denúncia registrada com sucesso!</h1>";
    echo "<p>Obrigado por contribuir com a proteção do meio ambiente.</p>";
    echo "<a href='index.html'>Voltar para o EcoAlert</a>";
} else {
    echo "Erro ao salvar denúncia: " . $stmt->error;
}

$stmt->close();
$conexao->close();
?>
