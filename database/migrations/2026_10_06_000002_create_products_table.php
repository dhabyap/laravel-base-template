<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Example table for CRUD practice. Copy this pattern for new modules.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->bigInteger('price')->default(0); // store amount without decimals
            $table->integer('stock')->default(0);
            $table->timestamps();

            // Index: name often searched in list/filter.
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
