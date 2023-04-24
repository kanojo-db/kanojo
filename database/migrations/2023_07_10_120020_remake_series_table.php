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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->nullable()->unique();
            $table->timestamps();
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->foreignId('series_id')->nullable()->after('studio_id')->constrained('series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // Drop the foreign key constraint on the series_id column first
            $table->dropForeign(['series_id']);
            // Remove the series_id column
            $table->dropColumn('series_id');
        });

        Schema::dropIfExists('series');
    }
};
