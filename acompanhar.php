<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

require_once "conexao.php";

$busca = trim($_GET["busca"] ?? "");
$denuncias = [];

if ($busca !== "") {
    $termo = "%" . $busca . "%";
    $sql = "SELECT id, nome, email, categoria, descricao, endereco, latitude, longitude, status_denuncia, criado_em
            FROM denuncias
            WHERE nome LIKE ? OR email LIKE ? OR categoria LIKE ? OR endereco LIKE ? OR id = ?
            ORDER BY criado_em DESC";
    $stmt = $conexao->prepare($sql);
    $idBusca = is_numeric($busca) ? (int) $busca : 0;
    $stmt->bind_param("ssssi", $termo, $termo, $termo, $termo, $idBusca);
} else {
    $sql = "SELECT id, nome, email, categoria, descricao, endereco, latitude, longitude, status_denuncia, criado_em
            FROM denuncias
            ORDER BY criado_em DESC
            LIMIT 20";
    $stmt = $conexao->prepare($sql);
}

if ($stmt && $stmt->execute()) {
    $resultado = $stmt->get_result();
    while ($linha = $resultado->fetch_assoc()) {
        $denuncias[] = $linha;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoAlert | Acompanhar Denúncias</title>
  <link rel="stylesheet" href="css/style.css">
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
      <a href="login.php?sair=1" style="margin-left: auto; color: #e74c3c;">Sair</a>
    </nav>
  </header>

  <section class="pagina-hero">
    <p class="etiqueta">Acompanhamento</p>
    <h1>Consultar denúncias</h1>
    <p>Pesquise pelo número da denúncia, nome, e-mail, categoria ou endereço informado.</p>
  </section>

  <main>
    <section class="secao">
      <form class="form-busca" method="GET" action="acompanhar.php">
        <label for="busca">Buscar denúncia</label>
        <div class="linha-busca">
          <input type="text" id="busca" name="busca" value="<?php echo htmlspecialchars($busca); ?>" placeholder="Ex: 1, Queimada, nome ou e-mail">
          <button class="botao botao-principal" type="submit">Pesquisar</button>
        </div>
      </form>

      <div class="lista-denuncias">
        <?php if (count($denuncias) === 0): ?>
          <div class="card-info">
            <h2>Nenhuma denúncia encontrada</h2>
            <p>Quando houver denúncias cadastradas no banco de dados, elas aparecerão nesta página.</p>
            <a class="botao botao-principal" href="index.html#denuncia">Registrar denúncia</a>
          </div>
        <?php else: ?>
          <?php foreach ($denuncias as $denuncia): ?>
            <article class="denuncia-card">
              <div class="denuncia-topo">
                <h2>Denúncia #<?php echo htmlspecialchars($denuncia["id"]); ?></h2>
                <span><?php echo htmlspecialchars($denuncia["status_denuncia"]); ?></span>
              </div>
              <p><strong>Categoria:</strong> <?php echo htmlspecialchars($denuncia["categoria"]); ?></p>
              <p><strong>Local:</strong> <?php echo htmlspecialchars($denuncia["endereco"]); ?></p>
              <p><strong>Coordenadas:</strong> <?php echo htmlspecialchars($denuncia["latitude"]); ?>, <?php echo htmlspecialchars($denuncia["longitude"]); ?></p>
              <p><strong>Descrição:</strong> <?php echo htmlspecialchars($denuncia["descricao"]); ?></p>
              <p><strong>Data:</strong> <?php echo htmlspecialchars($denuncia["criado_em"]); ?></p>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2026 EcoAlert - Sistema de Denúncias Ambientais</p>
  </footer>
</body>
</html>
<?php
if (isset($stmt) && $stmt) {
    $stmt->close();
}

$conexao->close();
?>
