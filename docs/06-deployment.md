# 06 Deployment

Checklist sebelum produksi.

## 1. Setting

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.domain.com
```

- `APP_DEBUG` wajib `false`
- `APP_KEY` sudah ada, jangan ganti sembarangan
- DB pakai user khusus, bukan `root` tanpa password

## 2. Database

```bash
php artisan migrate --force
```

Jangan `migrate:refresh` di produksi. Data hilang.

Seed demo (`admin@example.com`) hanya untuk dev. Jangan jalan di produksi. Buat admin via:

```bash
php artisan tinker
```

```php
User::create([
  'name' => 'Admin',
  'email' => 'admin@domain.com',
  'password' => 'ganti-password-kuat',
  'role' => 'admin',
]);
```

## 3. Optimasi

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --no-dev --optimize-autoloader
```

## 4. Keamanan

- [ ] Rate limit login tetap aktif
- [ ] Password minimal 8, hash via cast `hashed`
- [ ] Token Sanctum lewat HTTPS saja
- [ ] CORS batasi domain frontend
- [ ] Backup DB terjadwal
- [ ] Log error dipantau (`storage/logs/laravel.log`)

## 5. Update rutin

```bash
composer update
php artisan migrate --force
php artisan test
```
