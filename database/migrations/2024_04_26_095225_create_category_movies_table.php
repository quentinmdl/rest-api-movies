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
        Schema::create('category_movies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id');
            $table->foreignUuid('movie_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')
                 ->on('categories')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')
                ->on('movies')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_movies');
    }
};
