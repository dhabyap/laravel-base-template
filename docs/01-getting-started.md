# 01 Getting Started

Panduan dari nol sampai API jalan. Untuk pemula.

## 1. Apa template ini?

REST API siap pakai. Isi:

- Daftar + login pakai token
- Contoh data produk (tambah, lihat, ubah, hapus)
- Jawaban JSON selalu bentuk sama
- Test otomatis 9 kasus

Cocok untuk backend aplikasi mobile, frontend SPA, atau belajar pola Laravel benar.

## 2. Yang perlu diinstal

- PHP 8.3 atau lebih baru
- Composer 2
- Git
- MySQL 8 (opsional, dev bisa SQLite)

Cek:

```bash
php -v
composer -V
```

## 3. Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Artinya:

- `composer install` = unduh library
- `cp .env.example .env` = buat file setting lokal
- `key:generate` = buat kunci enkripsi app

## 4. Database

Pilih satu.

### Opsi A: SQLite (paling gampang)

Cocok untuk belajar. Tanpa instal DB.

```bash
# .env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Lalu:

```bash
php artisan migrate --seed
```

### Opsi B: MySQL

```bash
mysql -u root -e "CREATE DATABASE laravel_base;"
```

Ubah `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_base
DB_USERNAME=root
DB_PASSWORD=
```

Lalu:

```bash
php artisan migrate --seed
```

## 5. Jalankan

```bash
php artisan serve --port=8010
```

Buka `http://localhost:8010/up`. Balasan `OK` = server hidup.

## 6. Coba login

Akun demo dari seeder:

```text
admin@example.com / password123
user@example.com / password123
```

```bash
curl -X POST http://localhost:8010/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

Simpan `data.token`. Pakai untuk request berikut:

```bash
curl http://localhost:8010/api/v1/me \
  -H "Authorization: Bearer TOKEN"
```

## 7. Jalankan test

```bash
php artisan test
```

Harus `9 passed`. Test pakai SQLite memory, tidak ganggu DB dev.

## 8. Masalah umum

| Gejala | Sebab | Obat |
|---|---|---|
| `No application encryption key` | `.env` belum ada key | `php artisan key:generate` |
| `Connection refused` MySQL | DB belum jalan / nama salah | cek `DB_*` di `.env`, buat database |
| `401 Belum login` | token salah / lupa header | pakai `Authorization: Bearer TOKEN` |
| `422 Validasi gagal` | input kurang / salah format | baca `errors` di respons |
| `429 Too Many Requests` | login > 5x/menit | tunggu 1 menit |

Lanjut: [02 Architecture](02-architecture.md).
