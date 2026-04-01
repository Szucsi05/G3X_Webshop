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
        Schema::table('orders', function (Blueprint $table) {
            // Eltávolítjuk a régi items JSON-t és licenses-t
            // (az order_items tábla fogja kezelni az items-ket)
            $table->dropColumn(['items', 'licenses']);
            
            // Status mező hozzáadása
            $table->enum('status', ['pending', 'paid', 'processing', 'completed', 'cancelled'])->default('pending');
            
            // Rendelés deviza (opcionális)
            $table->string('currency')->default('USD');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Visszaállítjuk az eredeti mezőket
            $table->json('items')->nullable();
            $table->json('licenses')->nullable();
            
            // Eltávolítjuk az új mezőket
            $table->dropColumn(['status', 'currency']);
        });
    }
};
