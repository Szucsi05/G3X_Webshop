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
        Schema::table('products', function (Blueprint $table) {
            // Eltávolítjuk az eladó-specifikus mezőket
            $table->dropColumn(['image', 'platform', 'genre', 'category', 'release_year', 'prices']);
            
            // Hozzáadjuk a kategória referenciát
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Complex migration - rollback not supported for this migration
        // To rollback, clear the database and run migrations from scratch
    }
};
