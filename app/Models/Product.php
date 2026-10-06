<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model = 1 file represents 1 table (products).
// Contains: table name + fillable fields + relations (if any).
class Product extends Model
{
    use HasFactory;

    // Table is products (auto from Product name). Written explicit for clarity.
    protected $table = 'products';

    // Fields allowed for mass assignment (create/update with array).
    // Fields missing here get rejected by Laravel (prevents is_admin injection).
    protected $fillable = ['name', 'price', 'stock'];
}
