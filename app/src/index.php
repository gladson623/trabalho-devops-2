<?php
require __DIR__ . '/db.php';
$busca = trim($_GET['q'] ?? '');
$stmt = db()->prepare('SELECT * FROM produtos WHERE nome LIKE :q ORDER BY id DESC');
$stmt->execute([':q' => "%$busca%"]);
$produtos = $stmt->fetchAll();
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produtos</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
  <header>
    <h1>Produtos</h1>
    <a class="btn" href="form.php">Novo produto</a>
  </header>

  <?php if ($msg): ?><p class="aviso"><?= e($msg) ?></p><?php endif; ?>

  <form method="get" class="busca">
    <input type="search" name="q" placeholder="Buscar por nome" value="<?= e($busca) ?>">
    <button class="btn sec">Buscar</button>
  </form>

  <?php if (!$produtos): ?>
    <p class="vazio">Nenhum produto encontrado. Cadastre o primeiro em “Novo produto”.</p>
  <?php else: ?>
  <div class="tabela">
    <table>
      <thead><tr><th>#</th><th>Nome</th><th class="num">Preço</th><th class="num">Estoque</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($produtos as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><strong><?= e($p['nome']) ?></strong><br><small><?= e($p['descricao']) ?></small></td>
          <td class="num"><?= brl((float)$p['preco']) ?></td>
          <td class="num"><?= $p['estoque'] ?></td>
          <td class="acoes">
            <a href="form.php?id=<?= $p['id'] ?>">Editar</a>
            <form method="post" action="delete.php" onsubmit="return confirm('Excluir este produto?')">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button class="link perigo">Excluir</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</main>
</body>
</html>
