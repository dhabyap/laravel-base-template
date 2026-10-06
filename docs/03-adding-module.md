# 03 Adding Module

Tambah CRUD baru dengan meniru pola `Product`. Contoh: modul `Order`.

## Urutan kerja

1. Migration
2. Model
3. Request
4. Resource
5. Service
6. Controller
7. Route
8. Test manual + otomatis

## 1. Migration

```bash
php artisan make:migration create_orders_table
```

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->bigInteger('total')->default(0);
    $table->string('status', 20)->default('unpaid');
    $table->timestamps();
    $table->index(['user_id', 'status']);
});
```

```bash
php artisan migrate
```

## 2. Model

Salin [Product.php](../app/Models/Product.php) jadi `app/Models/Order.php`:

```php
class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['user_id', 'total', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

## 3. Request

Salin `ProductRequest.php` jadi `OrderRequest.php`:

```php
public function rules(): array
{
    return [
        'total' => 'required|integer|min:0',
        'status' => 'sometimes|in:unpaid,paid,cancelled',
    ];
}
```

## 4. Resource

Salin `ProductResource.php` jadi `OrderResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'total' => (int) $this->total,
        'status' => $this->status,
    ];
}
```

## 5. Service

Salin `ProductService.php` jadi `OrderService.php`:

```php
class OrderService
{
    public function list(int $perPage = 20)
    {
        return Order::query()
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return Order::create($data + ['user_id' => auth()->id()]);
    }
}
```

Multi-tulis wajib transaksi:

```php
return DB::transaction(function () use ($data) {
    $order = Order::create($data);
    // potong saldo, kurangi stok, kirim notif
    return $order;
});
```

## 6. Controller

Salin `ProductController.php` jadi `OrderController.php`. Ganti nama class dan type-hint. Pola method tetap:

```php
public function store(OrderRequest $request)
{
    $order = $this->service->create($request->validated());

    return $this->ok(new OrderResource($order), 'Order dibuat', 201);
}
```

## 7. Route

Tambah di [routes/api.php](../routes/api.php):

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('orders', OrderController::class);
});
```

Khusus admin:

```php
Route::apiResource('orders', OrderController::class)->middleware('role:admin');
```

## 8. Cek

```bash
curl -X POST http://localhost:8010/api/v1/orders \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"total":50000}'
```

Checklist:

- [ ] Input salah = `422`, bukan `500`
- [ ] Tanpa token = `401`
- [ ] User A tidak lihat data user B
- [ ] Controller tanpa `DB::` / `where` langsung
- [ ] Tambah Feature test untuk endpoint baru
