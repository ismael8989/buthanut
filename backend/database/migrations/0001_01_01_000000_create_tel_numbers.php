<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tel_numbers', function (Blueprint $table) {
            $table->string('tel');
            $table->boolean('verified')->default(false);
            $table->primary('tel');
        });       
    }

    public function down(): void
    {
        Schema::dropIfExists('tel_numbers');
    }
};
