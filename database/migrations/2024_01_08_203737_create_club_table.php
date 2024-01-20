<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->integer('club_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('tla', 255)->nullable();
            $table->string('crest', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->integer('founded')->nullable();
            $table->json('coach')->nullable();
            $table->json('squad')->nullable();
            $table->unsignedBigInteger('competition_id')->nullable();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club');
    }
};
