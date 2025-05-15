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
        Schema::create('consolidated_grn_data', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->string('location_code');
            $table->integer('masterclient_id')->nullable();
            $table->integer('total_grn_items')->nullable();
            $table->integer('remaining_grn')->nullable();
            $table->integer('total_okay_quantity')->nullable();
            $table->integer('total_rejected_quantity')->nullable();
            $table->integer('grn_done')->nullable();
            $table->integer('total_grns')->nullable();
            $table->float('efficiency')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consolidated_grn_data');
    }
};
