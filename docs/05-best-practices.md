# 05 Best Practices

Aturan wajib template ini. Ikuti agar kode tetap bersih.

## 1. Controller tipis

Controller hanya 3 baris pola:

```php
$data = $request->validated();
$result = $this->service->doSomething($data);

return $this->ok(new SomeResource($result), 'Berhasil');
```

Dilarang di controller:

- `DB::`, `Model::where`, `Model::create`
- Hitung harga, pajak, diskon
- `response()->json` manual (pakai `ok()` / `fail()`)

## 2. Validasi di Request

Semua input luar wajib lewat FormRequest.

```php
public function rules(): array
{
    return [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ];
}
```

Jangan cek manual `if (empty(...))` di controller.

## 3. Bisnis di Service

Query, transaksi, dan aturan milik Service.

```php
return DB::transaction(function () use ($data) {
    return Order::create($data);
});
```

Service kembalikan model / array. Jangan kembalikan HTTP response.

## 4. Format di Resource

Ubah bentuk JSON hanya di Resource.

```php
return [
    'id' => $this->id,
    'price' => (int) $this->price,
];
```

Jangan tambah field respons di controller.

## 5. Respons seragam

Pakai trait `ApiResponse`:

- Sukses: `$this->ok($data, 'Pesan', 200)`
- Gagal: `$this->fail('Pesan', 422, $errors)`

Jangan buat format `{success, data}` lain.

## 6. Auth di route

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
});
```

Jangan cek `auth()->user()` manual untuk tolak akses. Untuk admin:

```php
->middleware('role:admin')
```

## 7. Query hemat

- Pilih kolom perlu: `select(['id', 'name'])`
- Paginasi, jangan `get()` semua
- Relasi pakai eager load: `with('user')`
- Filter milik user: `where('user_id', auth()->id())`

## 8. Gaya kode

```bash
./vendor/bin/pint
php artisan test
```

- Type-hint parameter dan return
- Nama jelas: `OrderService`, bukan `Helper`
- Satu class satu tugas
- Komentar hanya untuk alasan tidak jelas, bukan ulangi kode
