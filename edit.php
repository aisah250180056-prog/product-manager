<?php

require 'config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die('ID produk tidak ditemukan.');
}

$stmt = $pdo->prepare(
    'SELECT * FROM products WHERE id = ?'
);

$stmt->execute([$id]);

$product = $stmt->fetch();

if (!$product) {
    die('Produk tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;

    $stmt = $pdo->prepare(
        'UPDATE products
         SET name = ?, category = ?, price = ?, stock = ?
         WHERE id = ?'
    );

    $stmt->execute([
        $name,
        $category,
        $price,
        $stock,
        $id
    ]);

    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Produk</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>

<div class="container small-container">

    <a
        href="index.php"
        class="back"
    >
        ← Kembali ke daftar
    </a>

    <section class="panel">

        <h1>Edit Produk</h1>

        <form
            method="POST"
            class="product-form"
        >

            <label>

                Nama Produk

                <input
                    type="text"
                    name="name"
                    required
                    value="<?= htmlspecialchars($product['name']) ?>"
                >

            </label>


            <label>

                Kategori

                <input
                    type="text"
                    name="category"
                    required
                    value="<?= htmlspecialchars($product['category']) ?>"
                >

            </label>


            <label>

                Harga

                <input
                    type="number"
                    name="price"
                    min="0"
                    required
                    value="<?= htmlspecialchars($product['price']) ?>"
                >

            </label>


            <label>

                Stok

                <input
                    type="number"
                    name="stock"
                    min="0"
                    required
                    value="<?= htmlspecialchars($product['stock']) ?>"
                >

            </label>


            <button
                type="submit"
                class="btn primary"
            >
                Simpan Perubahan
            </button>

        </form>

    </section>

</div>

</body>

</html>