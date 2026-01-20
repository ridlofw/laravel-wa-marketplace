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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'shop_name')) {
                $table->string('shop_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'shop_address')) {
                $table->text('shop_address')->nullable();
            }
            if (!Schema::hasColumn('users', 'shop_whatsapp')) {
                $table->string('shop_whatsapp')->nullable();
            }
            if (!Schema::hasColumn('users', 'shop_logo')) {
                $table->string('shop_logo')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['shop_name', 'shop_address', 'shop_whatsapp', 'shop_logo']);
        });
    }
};
