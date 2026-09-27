# Mini Project — Product Manager

Aplikasi manajemen produk menggunakan PHP + MySQL.

## Fitur sesuai instruksi
- Create: nama, kategori, harga, stok
- Read: daftar produk dalam card responsif
- Update: edit data produk
- Delete: menggunakan POST + CSRF
- Validasi server-side:
  - nama minimal 3 karakter
  - harga >= 0
  - stok >= 0
  - nama produk unik
- PDO prepared statement untuk INSERT, SELECT, UPDATE, DELETE
- `htmlspecialchars()` untuk output HTML
- PRG (Post/Redirect/Get) setelah create agar refresh tidak membuat duplikasi
- Responsive untuk layar sempit

## Cara menjalankan
1. Aktifkan Apache dan MySQL di XAMPP.
2. Salin folder `mini_project_product_manager` ke `htdocs`.
3. Buka phpMyAdmin.
4. Import file `db.sql`.
5. Jika username/password MySQL berbeda, ubah bagian koneksi di `config.php`.
6. Buka:
   `http://localhost/mini_project_product_manager/`

## Uji checklist
1. Tambah produk valid -> muncul di daftar.
2. Nama 1–2 karakter -> ditolak.
3. Harga negatif -> ditolak.
4. Stok negatif -> ditolak.
5. Tambah produk dengan nama sama -> ditolak.
6. Refresh setelah create -> tidak ada duplikasi.
7. Nama `<b>Promo</b>` -> tampil sebagai teks, bukan HTML.
8. Perkecil layar -> card membungkus menjadi satu kolom.
