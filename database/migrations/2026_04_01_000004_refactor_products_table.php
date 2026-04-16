<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            
            $table->dropColumn(['image', 'platform', 'genre', 'category', 'release_year', 'prices']);
            
            
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        });
    }

    
    public function down(): void
    {
        
        
    }
};
