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
        Schema::create('habit_completes', function (Blueprint $table) {
            $table->id('hac_id');
            $table->foreignId('hac_hab_id')->constrained('habits', 'hab_id');
            $table->date('hac_date');
            $table->text('hac_notes')->nullable();
            $table->integer('hac_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_completes');
    }
};
