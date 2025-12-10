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
        Schema::create('canonical_tracks', function (Blueprint $table) {
            $table->id();

            $table->string('title', 255);
            $table->unsignedBigInteger('raw_artist_id')->nullable()->index();
            $table->decimal('bpm', 5, 2)->nullable();
            $table->unsignedSmallInteger('duration');
            $table->string('key', 5)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canonical_tracks');
    }
};
