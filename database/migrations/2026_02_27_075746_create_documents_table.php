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
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');                 // Primary key, auto-increment, unsigned
            $table->string('title');                     // Document title
            $table->string('slug')->unique();            // Unique slug
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key to users.id
            $table->timestamps();                        // created_at & updated_at

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
