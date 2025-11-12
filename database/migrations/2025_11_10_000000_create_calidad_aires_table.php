<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calidad_aires', function (Blueprint $table) {
            $table->id();
            $table->decimal('calidad_aire', 5, 2); // igual que humedad
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calidad_aires');
    }
};
