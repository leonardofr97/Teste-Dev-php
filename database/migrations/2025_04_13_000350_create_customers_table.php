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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->min(5)->max(255)->required();
            $table->string('document', 11)->unique()->required();
            $table->string('email')->unique()->required();
            $table->string('phone')->nullable();
            $table->string('zip_code')->required();
            $table->string('street')->required();
            $table->string('district')->required();
            $table->string('city')->required();
            $table->string('state')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
