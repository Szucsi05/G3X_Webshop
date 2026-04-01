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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_offer_id')->constrained('product_offers')->onDelete('cascade');
            
            // Az ár a vásárlás időpontjában (nem dinamikus)
            $table->decimal('price_at_purchase', 10, 2);
            $table->integer('quantity')->default(1);
            
            // Aktiválási kulcs vagy account adatok (ha szükséges)
            $table->text('license_key')->nullable();
            $table->json('account_details')->nullable(); // pl. felhasználónév, jelszó (encrypted)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
