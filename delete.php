<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    flash('error', 'ID produk tidak valid.');
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    flash('success', 'Produk berhasil dihapus.');
} else {
    flash('error', 'Produk tidak ditemukan.');
}

header('Location: index.php');
exit;
