<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            
            
            $table->dropColumn(['items', 'licenses']);
            
            
            $table->enum('status', ['pending', 'paid', 'processing', 'completed', 'cancelled'])->default('pending');
            
            
            $table->string('currency')->default('USD');
        });
    }

    
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            
            $table->json('items')->nullable();
            $table->json('licenses')->nullable();
            
            
            $table->dropColumn(['status', 'currency']);
        });
    }
};
