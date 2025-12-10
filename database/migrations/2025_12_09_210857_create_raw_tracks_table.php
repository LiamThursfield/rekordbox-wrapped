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
        Schema::create('raw_tracks', function (Blueprint $table) {
            $table->id();

            $table->string('title', 255);
            $table->unsignedBigInteger('raw_artist_id')->nullable();
            $table->decimal('bpm', 5, 2)->nullable();
            $table->unsignedSmallInteger('duration');
            $table->string('key', 5)->nullable();

            $table->unsignedBigInteger('canonical_track_id')->nullable()->index();

            $table->string('provider', 15);
            $table->string('provider_id', 255);

            $table->timestamps();

            $table->index(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_tracks');
    }
};
