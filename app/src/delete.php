<?php
require __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) db()->prepare('DELETE FROM produtos WHERE id = ?')->execute([$id]);
}
header('Location: index.php?msg=' . urlencode('Produto excluído.'));
