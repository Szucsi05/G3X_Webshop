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
        Schema::create('product_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('platform_id')->constrained('platforms')->onDelete('cascade');
            
            // Ár és készlet
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            
            // Opcionális mezők
            $table->string('region')->nullable(); // pl. EU, US, GLOBAL
            $table->enum('delivery_type', ['key', 'account', 'gift', 'physical'])->default('key'); // kulcs, fiók, ajándék, fizikai
            
            // Status
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            
            $table->timestamps();
            
            // Unique constraint: egy eladó nem adhat duplikált ajánlatot ugyanarra a termékre-platformra
            $table->unique(['product_id', 'vendor_id', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_offers');
    }
};
