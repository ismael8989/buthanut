<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_numbers', function (Blueprint $table) {
            $table->string('tel');
            $table->string('verification_number');
            $table->timestamp('exp');
            $table->primary('tel');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_numbers');
    }
};
