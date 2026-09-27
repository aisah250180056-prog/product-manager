<?php
require 'config.php';
require 'functions.php';

$stmt = $pdo->query('SELECT id, name, category, price, stock, created_at FROM products ORDER BY id DESC');
$products = $stmt->fetchAll();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="hero">
        <div>
            <span class="badge">MINI PROJECT • PHP + MySQL</span>
            <h1>Product Manager</h1>
            <p>Kelola produk dengan validasi, prepared statement, CSRF, dan output yang aman.</p>
        </div>
    </header>

    <?php if ($flash): ?>
        <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <section class="panel">
        <div class="section-title">
            <div>
                <h2>Tambah Produk</h2>
                <p>Isi data produk baru di bawah ini.</p>
            </div>
        </div>

        <form action="create.php" method="POST" class="product-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

            <label>
                Nama Produk
                <input type="text" name="name" minlength="3" required placeholder="Contoh: Beras Premium">
            </label>

            <label>
                Kategori
                <input type="text" name="category" required placeholder="Contoh: Sembako">
            </label>

            <label>
                Harga
                <input type="number" name="price" min="0" step="0.01" required placeholder="0">
            </label>

            <label>
                Stok
                <input type="number" name="stock" min="0" step="1" required placeholder="0">
            </label>

            <button type="submit" class="btn primary">+ Tambah Produk</button>
        </form>
    </section>

    <section class="products-section">
        <div class="section-title">
            <div>
                <h2>Daftar Produk</h2>
                <p><?= count($products) ?> produk tersimpan.</p>
            </div>
        </div>

        <?php if (!$products): ?>
            <div class="empty">Belum ada produk. Silakan tambahkan produk pertama.</div>
        <?php else: ?>
            <div class="cards">
                <?php foreach ($products as $product): ?>
                    <article class="card">
                        <div class="card-top">
                            <span class="category"><?= e($product['category']) ?></span>
                            <span class="stock <?= (int)$product['stock'] === 0 ? 'empty-stock' : '' ?>">
                                Stok: <?= e((string)$product['stock']) ?>
                            </span>
                        </div>

                        <h3><?= e($product['name']) ?></h3>
                        <p class="price">Rp <?= number_format((float)$product['price'], 0, ',', '.') ?></p>

                        <div class="actions">
                            <a class="btn edit" href="edit.php?id=<?= (int)$product['id'] ?>">Edit</a>

                            <form action="delete.php" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
                                <button class="btn danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="reflection">
        <h2>Refleksi Keamanan</h2>
        <p>
            Titik rawan utama ada pada input pengguna, query database, output HTML, dan alur request.
            Proyek ini menggunakan validasi server-side, prepared statement PDO, unique constraint,
            CSRF token untuk request POST, serta <code>htmlspecialchars()</code> saat menampilkan data.
        </p>
    </section>
</div>
</body>
</html>
