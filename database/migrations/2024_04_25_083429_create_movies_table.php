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
        Schema::create('movies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 128)->nullable(false);
            $table->text('description', 4096)->nullable(false);
            $table->date('release_date')->nullable(false);
            $table->foreignUuid('media_id')->nullable();
            $table->integer('rate')->nullable(false)->unsigned();
            $table->integer('duration')->nullable(false)->unsigned();
            $table->timestamps();
            
            $table->foreign('media_id')->references('id')->on('medias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
