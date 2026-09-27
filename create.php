<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

verify_csrf();

$data = [
    'name' => trim($_POST['name'] ?? ''),
    'category' => trim($_POST['category'] ?? ''),
    'price' => $_POST['price'] ?? '',
    'stock' => $_POST['stock'] ?? '',
];

$errors = validate_product($data, $pdo);

if ($errors) {
    flash('error', implode(' ', $errors));
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)'
    );
    $stmt->execute([
        ':name' => $data['name'],
        ':category' => $data['category'],
        ':price' => (float)$data['price'],
        ':stock' => (int)$data['stock'],
    ]);

    // PRG (Post/Redirect/Get): setelah create, redirect agar refresh tidak membuat duplikasi.
    flash('success', 'Produk berhasil ditambahkan.');
} catch (PDOException $e) {
    flash('error', 'Produk gagal ditambahkan. Pastikan nama produk unik.');
}

header('Location: index.php');
exit;
