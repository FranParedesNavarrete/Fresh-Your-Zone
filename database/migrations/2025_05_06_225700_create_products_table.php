<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('name', 50);
            $table->string('slug')->unique();
            $table->decimal('price', 6, 2);
            $table->string('description', 255);
            $table->enum('category', ['accesorios', 'calzado', 'deporte', 'formal', 'casual', 'invierno', 'verano', 'abrigo', 'camisetas', 'pantalones']);
            $table->enum('state', ['nuevo', 'semi-nuevo', 'usado', 'en buen estado', 'desgastado', 'vintaege']);
            $table->integer('stock')->check('stock >= 0 AND stock <= 999');
            $table->string('images', 255);

            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
