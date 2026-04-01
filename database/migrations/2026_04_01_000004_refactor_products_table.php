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
        Schema::table('products', function (Blueprint $table) {
            // Visszaállítjuk az eredeti mezőket
            $table->string('image')->nullable();
            $table->string('platform')->nullable();
            $table->string('genre')->nullable();
            $table->string('category')->nullable();
            $table->integer('release_year')->nullable();
            $table->json('prices')->nullable();
            
            // Eltávolítjuk a kategória referenciát
            $table->dropForeignKeyIfExists(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
