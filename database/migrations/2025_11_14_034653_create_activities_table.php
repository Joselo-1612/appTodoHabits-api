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
        Schema::create('activities', function (Blueprint $table) {
            $table->id('act_id');
            $table->string('act_name');
            $table->string('act_description')->nullable();
            $table->date('act_date_start');
            $table->date('act_date_end');
            $table->foreignId('act_sea_id')->constrained('activity_sections', 'acs_id');
            $table->integer('act_position');
            $table->integer('act_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
