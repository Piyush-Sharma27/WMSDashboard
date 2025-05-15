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
        Schema::create('grn_old_data', function (Blueprint $table) {
            $table->id();
            // $table->string('grnno')->nullable();
            $table->string('timeslot')->nullable();
            $table->string('client_id')->nullable();
               $table->string('location_code')->nullable();
               $table->string('masterclient_id')->nullable();
               $table->integer('total_quantity')->nullable();
               $table->integer('remaining_grn')->nullable();
               $table->integer('total_okay_quantity')->nullable();
               $table->integer('total_rejected_quantity')->nullable();
            //    $table->integer('grn_processed')->nullable();
            //    $table->integer('items_processed')->nullable();
               $table->boolean('grn_done')->nullable();
              
               $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_old_data');
    }
};
