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
        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('remaining_grn');
            $table->integer('total_grn_items');
            $table->integer('total_okay_quantity');
            $table->integer('total_rejected_quantity');
            $table->integer('grn_done');
            $table->integer('efficiency');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grns');
    }
};
