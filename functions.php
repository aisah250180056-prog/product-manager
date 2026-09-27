<?php
session_start();

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Permintaan tidak valid (CSRF token salah).');
    }
}

function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function validate_product(array $data, PDO $pdo, ?int $ignoreId = null): array {
    $errors = [];

    $name = trim($data['name'] ?? '');
    $category = trim($data['category'] ?? '');
    $price = $data['price'] ?? '';
    $stock = $data['stock'] ?? '';

    if (mb_strlen($name) < 3) {
        $errors[] = 'Nama produk minimal 3 karakter.';
    }

    if ($category === '') {
        $errors[] = 'Kategori wajib diisi.';
    }

    if ($price === '' || !is_numeric($price) || (float)$price < 0) {
        $errors[] = 'Harga tidak boleh negatif dan harus berupa angka.';
    }

    if ($stock === '' || filter_var($stock, FILTER_VALIDATE_INT) === false || (int)$stock < 0) {
        $errors[] = 'Stok tidak boleh negatif dan harus berupa bilangan bulat.';
    }

    if ($name !== '') {
        if ($ignoreId === null) {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE LOWER(name) = LOWER(?)');
            $stmt->execute([$name]);
        } else {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE LOWER(name) = LOWER(?) AND id <> ?');
            $stmt->execute([$name, $ignoreId]);
        }

        if ((int)$stmt->fetchColumn() > 0) {
            $errors[] = 'Nama produk sudah digunakan. Gunakan nama yang unik.';
        }
    }

    return $errors;
}
