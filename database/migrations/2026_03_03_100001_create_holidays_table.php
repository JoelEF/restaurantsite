<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('name')->nullable(); // Bijv. "Kerst", "Oud&Nieuw", "Vakantie"
            $table->boolean('is_closed')->default(true);
            $table->time('open_time')->nullable();  // Als niet gesloten, aangepaste tijd
            $table->time('close_time')->nullable();
            $table->timestamps();

            $table->unique('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
