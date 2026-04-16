<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method'); 
            $table->json('items')->nullable(); 
            $table->string('billing_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postal')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_company_name')->nullable();
            $table->string('billing_tax_id')->nullable();
            $table->string('account_type')->nullable(); 
            $table->json('licenses')->nullable(); 
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
