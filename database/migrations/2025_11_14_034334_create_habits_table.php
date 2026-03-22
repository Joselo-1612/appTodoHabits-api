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
        Schema::create('habits', function (Blueprint $table) {
            $table->id('hab_id');
            $table->string('hab_name');
            $table->text('hab_description')->nullable();
            $table->enum('hab_type_recurrence', ['diaria', 'semanal', 'mensual', 'personalizado']);
            $table->integer('hab_status')->default(1);
            $table->foreignId('hab_use_id')->constrained('users', 'usu_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
