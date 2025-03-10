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
        Schema::create('license_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('licensing_category_id')->constrained();
            $table->string('name');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_documents');
    }
};
