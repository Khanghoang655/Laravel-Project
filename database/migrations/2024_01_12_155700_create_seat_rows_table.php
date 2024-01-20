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
        Schema::create('seat_rows', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->nullable();
            $table->integer('total_seats')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('match_id');
            $table->foreign('match_id')->references('id')->on('football_matches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_rows');
    }
};
