<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carnets', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->unsignedBigInteger('buthanut_id');
            $table->timestamps();
            $table->foreign('buthanut_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carnets');
    }
};
