<?php
require __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }

$id        = (int)($_POST['id'] ?? 0);
$nome      = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco     = (float)($_POST['preco'] ?? 0);
$estoque   = (int)($_POST['estoque'] ?? 0);

if ($nome === '' || $preco < 0 || $estoque < 0) {
    header('Location: index.php?msg=' . urlencode('Dados inválidos. Verifique nome, preço e estoque.'));
    exit;
}

if ($id) {
    $sql = 'UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=? WHERE id=?';
    db()->prepare($sql)->execute([$nome, $descricao, $preco, $estoque, $id]);
    $msg = 'Produto atualizado.';
} else {
    $sql = 'INSERT INTO produtos (nome, descricao, preco, estoque) VALUES (?,?,?,?)';
    db()->prepare($sql)->execute([$nome, $descricao, $preco, $estoque]);
    $msg = 'Produto cadastrado.';
}
header('Location: index.php?msg=' . urlencode($msg));
