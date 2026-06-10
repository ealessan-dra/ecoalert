<?php
session_start();

// Verificar se o usuário está logado e se é administrador
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo_usuario"] !== "admin") {
    header("Location: login.php");
    exit;
}

if (isset($_GET["sair"])) {
    session_destroy();
    header("Location: index.html");
    exit;
}

$adminLogado = true;
$denuncias = [];
$totalDenuncias = 0;
$totalRecebidas = 0;
$totalCategorias = 0;

if ($adminLogado) {
    require_once "conexao.php";

    $categoriaFiltro = trim($_GET["categoria"] ?? "");
    $statusFiltro = trim($_GET["status"] ?? "");
    $busca = trim($_GET["busca"] ?? "");

    $condicoes = [];
    $parametros = [];
    $tipos = "";

    if ($categoriaFiltro !== "") {
        $condicoes[] = "categoria = ?";
        $parametros[] = $categoriaFiltro;
        $tipos .= "s";
    }

    if ($statusFiltro !== "") {
        $condicoes[] = "status_denuncia = ?";
        $parametros[] = $statusFiltro;
        $tipos .= "s";
    }

    if ($busca !== "") {
        $condicoes[] = "(nome LIKE ? OR email LIKE ? OR endereco LIKE ? OR descricao LIKE ? OR id = ?)";
        $termo = "%" . $busca . "%";
        $parametros[] = $termo;
        $parametros[] = $termo;
        $parametros[] = $termo;
        $parametros[] = $termo;
        $parametros[] = is_numeric($busca) ? (int) $busca : 0;
        $tipos .= "ssssi";
    }

    $where = count($condicoes) > 0 ? "WHERE " . implode(" AND ", $condicoes) : "";

    $sql = "SELECT id, nome, email, categoria, descricao, endereco, latitude, longitude, status_denuncia, criado_em
            FROM denuncias
            $where
            ORDER BY criado_em DESC";

    $stmt = $conexao->prepare($sql);

    if ($stmt && count($parametros) > 0) {
        $stmt->bind_param($tipos, ...$parametros);
    }

    if ($stmt && $stmt->execute()) {
        $resultado = $stmt->get_result();
        while ($linha = $resultado->fetch_assoc()) {
            $denuncias[] = $linha;
        }
    }

    $resultadoTotal = $conexao->query("SELECT COUNT(*) AS total FROM denuncias");
    if ($resultadoTotal) {
        $totalDenuncias = (int) $resultadoTotal->fetch_assoc()["total"];
    }

    $resultadoRecebidas = $conexao->query("SELECT COUNT(*) AS total FROM denuncias WHERE status_denuncia = 'Recebida'");
    if ($resultadoRecebidas) {
        $totalRecebidas = (int) $resultadoRecebidas->fetch_assoc()["total"];
    }

    $resultadoCategorias = $conexao->query("SELECT COUNT(DISTINCT categoria) AS total FROM denuncias");
    if ($resultadoCategorias) {
        $totalCategorias = (int) $resultadoCategorias->fetch_assoc()["total"];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoAlert | Administração</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="topo">
    <a class="logo" href="index.html">EcoAlert</a>

    <nav class="menu" aria-label="Menu principal">
      <a href="index.html">Início</a>
      <a href="index.html#denuncia">Fazer denúncia</a>
      <a href="acompanhar.html">Acompanhar denúncia</a>
      <a href="orientacoes.html">Orientações</a>
      <a href="contato.html">Contato</a>
    </nav>
  </header>

  <section class="pagina-hero">
    <p class="etiqueta">Área administrativa</p>
    <h1>Painel do administrador</h1>
    <p>Visualize as denúncias registradas no EcoAlert e acompanhe as informações enviadas pelos usuários.</p>
  </section>

  <main>
    <section class="secao">
      <div class="admin-topo">
        <div>
          <p class="etiqueta">Resumo</p>
          <h2>Denúncias registradas</h2>
        </div>
        <a class="botao botao-secundario" href="admin.php?sair=1">Sair</a>
      </div>

      <div class="admin-resumo">
        <article>
          <strong><?php echo $totalDenuncias; ?></strong>
          <span>Total de denúncias</span>
        </article>
        <article>
          <strong><?php echo $totalRecebidas; ?></strong>
          <span>Denúncias recebidas</span>
        </article>
        <article>
          <strong><?php echo $totalCategorias; ?></strong>
          <span>Categorias registradas</span>
        </article>
      </div>

      <form class="form-busca admin-filtros" method="GET" action="admin.php">
        <label for="busca">Pesquisar denúncia</label>
        <input type="text" id="busca" name="busca" value="<?php echo htmlspecialchars($busca ?? ""); ?>" placeholder="Número, nome, e-mail, local ou descrição">

        <div class="admin-campos">
          <div>
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria">
              <option value="">Todas as categorias</option>
              <?php
              $categorias = ["Desmatamento", "Queimada", "Poluição da água", "Descarte irregular de lixo", "Maus-tratos a animais", "Ocupação irregular"];
              foreach ($categorias as $categoria):
              ?>
                <option value="<?php echo htmlspecialchars($categoria); ?>" <?php echo (($categoriaFiltro ?? "") === $categoria) ? "selected" : ""; ?>>
                  <?php echo htmlspecialchars($categoria); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label for="status">Status</label>
            <select id="status" name="status">
              <option value="">Todos os status</option>
              <?php
              $statusLista = ["Recebida", "Em análise", "Encaminhada", "Resolvida"];
              foreach ($statusLista as $status):
              ?>
                <option value="<?php echo htmlspecialchars($status); ?>" <?php echo (($statusFiltro ?? "") === $status) ? "selected" : ""; ?>>
                  <?php echo htmlspecialchars($status); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <button class="botao botao-principal" type="submit">Filtrar denúncias</button>
      </form>

      <?php if (count($denuncias) === 0): ?>
        <div class="card-info">
          <h2>Nenhuma denúncia encontrada</h2>
          <p>Não há denúncias cadastradas com os filtros selecionados.</p>
        </div>
      <?php else: ?>
        <div class="tabela-admin">
          <table>
            <thead>
              <tr>
                <th>Número</th>
                <th>Denunciante</th>
                <th>Categoria</th>
                <th>Local</th>
                <th>Status</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($denuncias as $denuncia): ?>
                <tr>
                  <td>#<?php echo htmlspecialchars($denuncia["id"]); ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($denuncia["nome"]); ?></strong><br>
                    <span><?php echo htmlspecialchars($denuncia["email"]); ?></span>
                  </td>
                  <td><?php echo htmlspecialchars($denuncia["categoria"]); ?></td>
                  <td>
                    <?php echo htmlspecialchars($denuncia["endereco"]); ?><br>
                    <span><?php echo htmlspecialchars($denuncia["latitude"]); ?>, <?php echo htmlspecialchars($denuncia["longitude"]); ?></span>
                  </td>
                  <td><span class="status-admin"><?php echo htmlspecialchars($denuncia["status_denuncia"]); ?></span></td>
                  <td><?php echo htmlspecialchars($denuncia["criado_em"]); ?></td>
                </tr>
                <tr class="linha-descricao">
                  <td colspan="6">
                    <strong>Descrição:</strong> <?php echo htmlspecialchars($denuncia["descricao"]); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
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

if (isset($conexao)) {
    $conexao->close();
}
?>
