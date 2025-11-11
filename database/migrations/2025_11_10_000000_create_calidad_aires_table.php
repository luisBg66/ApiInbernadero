<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calidad_aires', function (Blueprint $table) {
            $table->id();
            $table->string('estado'); // bueno, malo, regular, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calidad_aires');
    }
};
