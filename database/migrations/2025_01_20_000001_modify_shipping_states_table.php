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
        Schema::table('shipping_states', function (Blueprint $table) {
            $table->foreignId('shipping_country_id')->after('id')->constrained('shipping_countries')->onDelete('cascade');
            $table->string('code', 10)->after('name')->nullable(); // State/province code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_states', function (Blueprint $table) {
            $table->dropForeign(['shipping_country_id']);
            $table->dropColumn(['shipping_country_id', 'code']);
        });
    }
}; 