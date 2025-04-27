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
        Schema::create('breathingExercise', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('inspirationDuration');
            $table->integer('apneaDuration');
            $table->integer('expirationDuration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breathingExercise');
    }
};
