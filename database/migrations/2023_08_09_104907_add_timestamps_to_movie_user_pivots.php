<?php

declare(strict_types=1);

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
        Schema::table('movie_user_collection', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('movie_user_favorite', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('movie_user_wishlist', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie_user_collection', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('movie_user_favorite', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('movie_user_wishlist', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
