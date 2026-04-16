<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('card_number')->nullable()->after('cart_data');
            $table->string('card_expiry')->nullable()->after('card_number');
            $table->string('card_cvv')->nullable()->after('card_expiry');
        });
    }

    
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['card_number', 'card_expiry', 'card_cvv']);
        });
    }
};
