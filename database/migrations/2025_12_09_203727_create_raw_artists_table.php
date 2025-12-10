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
        Schema::create('raw_artists', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);

            $table->unsignedBigInteger('canonical_artist_id')->nullable()->index();

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
        Schema::dropIfExists('raw_artists');
    }
};
