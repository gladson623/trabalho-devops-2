<?php
require __DIR__ . '/db.php';
$id = (int)($_GET['id'] ?? 0);
$p = ['nome' => '', 'descricao' => '', 'preco' => '', 'estoque' => 0];
if ($id) {
    $stmt = db()->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->execute([$id]);
    $p = $stmt->fetch();
    if (!$p) { header('Location: index.php?msg=' . urlencode('Produto não encontrado.')); exit; }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $id ? 'Editar' : 'Novo' ?> produto</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="estreito">
  <header><h1><?= $id ? 'Editar produto' : 'Novo produto' ?></h1></header>
  <form method="post" action="save.php" class="campos">
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>Nome
      <input name="nome" required maxlength="120" value="<?= e($p['nome']) ?>">
    </label>
    <label>Descrição
      <textarea name="descricao" rows="3"><?= e($p['descricao']) ?></textarea>
    </label>
    <div class="linha">
      <label>Preço (R$)
        <input name="preco" type="number" step="0.01" min="0" required value="<?= e((string)$p['preco']) ?>">
      </label>
      <label>Estoque
        <input name="estoque" type="number" min="0" required value="<?= (int)$p['estoque'] ?>">
      </label>
    </div>
    <div class="botoes">
      <button class="btn">Salvar produto</button>
      <a class="btn sec" href="index.php">Cancelar</a>
    </div>
  </form>
</main>
</body>
</html>
