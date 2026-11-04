# Time Capsule

Website untuk menulis pesan digital yang hanya dapat dibaca setelah tanggal dan waktu pembukaan. Isi dan judul pesan dienkripsi dengan Laravel. Sebelum waktunya, server hanya mengirim judul, jadwal, dan informasi countdown.

## Fitur

- Tulis pesan dan tentukan waktu pembukaan dalam Asia/Jakarta.
- Simpan jadwal dalam UTC; countdown mengikuti jam server.
- Tautan acak 64 karakter, dengan token yang disimpan sebagai hash.
- Validasi server, CSRF, rate limiting, dan halaman baca responsif.

## Teknologi

Laravel 13, PHP 8.4.1+, SQLite, Blade, CSS, Vanilla JavaScript, dan Vite. Controller menangani permintaan, layanan menentukan izin pembukaan, dan Blade menampilkan data yang sudah diizinkan.

## Menjalankan

Diperlukan Composer, Node 22.12+, serta ekstensi PHP SQLite.

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm ci
npm run build
php artisan serve --host=127.0.0.1
```

Buka http://127.0.0.1:8000, tulis pesan, tentukan waktu, lalu tekan **Simpan Pesan**. Simpan tautan yang diberikan; tidak ada login atau daftar pesan. Siapa pun yang memiliki tautan dapat membaca pesan setelah waktunya. Tautan localhost hanya berlaku di komputer server; akses lintas perangkat membutuhkan deployment bersama.

Jaga `APP_KEY` dan cadangannya: kehilangan kunci membuat pesan tidak dapat didekripsi. Jangan commit `.env` atau database. Enkripsi ini tidak menyembunyikan pesan dari pengelola server yang memiliki kunci. Saat deployment, gunakan HTTPS dan jam server yang tersinkron.

Pengujian:

```sh
php artisan test
npm test
npm run build
```
