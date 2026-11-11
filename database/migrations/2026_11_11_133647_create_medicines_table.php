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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('generic_name');
            $table->string('brand_name')->nullable();
            $table->string('dosage')->nullable(); // e.g. 500mg, 5mg, 100ml
            $table->string('category')->default('General'); // Maintenance, Antibiotics, Pain & Fever, Vitamins
            $table->string('unit')->default('tablets'); // tablets, capsules, bottles, sachets
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(50);
            $table->text('instructions')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
