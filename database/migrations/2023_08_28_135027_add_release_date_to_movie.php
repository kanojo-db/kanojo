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
        Schema::table('movies', function (Blueprint $table) {
            $table->date('release_date')->nullable()->after('popularity')->index();
        });

        DB::statement('UPDATE movies SET release_date = (SELECT MIN(release_date) FROM movie_versions WHERE movie_id = movies.id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie', function (Blueprint $table) {
            $table->dropColumn('release_date');
        });
    }
};
