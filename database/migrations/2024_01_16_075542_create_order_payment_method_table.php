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
        Schema::create('order_payment_method', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method')->nullable();
            $table->double('total')->nullable();
            $table->string('status',255)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment_method');
    }
};
