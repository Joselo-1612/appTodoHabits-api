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
        Schema::create('habit_days', function (Blueprint $table) {
            $table->id('had_id');
            $table->foreignId('had_hab_id')->constrained('habits', 'hab_id');
            $table->enum('had_day', [
                'lunes',
                'martes',
                'miercoles',
                'jueves',
                'viernes',
                'sabado',
                'domingo'
            ]);
            $table->string('had_description')->nullable();
            $table->dateTime('had_schedule')->nullable();
            $table->integer('had_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_days');
    }
};
