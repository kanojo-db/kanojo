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
        // Remove "breast_implants" column from "people" table
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('breast_implants');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->string('twitter_id')->nullable()->after('cup_size');
            $table->string('instagram_id')->nullable()->after('twitter_id');
            $table->string('tiktok_id')->nullable()->after('instagram_id');
            $table->string('ameblo_id')->nullable()->after('tiktok_id');
            $table->string('wikidata_id')->nullable()->after('ameblo_id');
            $table->string('youtube_id')->nullable()->after('wikidata_id');
            $table->string('google_id')->nullable()->after('youtube_id');
            $table->string('imdb_id')->nullable()->after('google_id');
            $table->string('fanza_id')->nullable()->after('imdb_id');
            $table->string('tmdb_id')->nullable()->after('fanza_id');
            $table->string('line_blog_id')->nullable()->after('tmdb_id');
            $table->string('onlyfans_id')->nullable()->after('line_blog_id');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->string('imdb_id')->nullable()->after('slug');
            $table->string('tmdb_id')->nullable()->after('imdb_id');
            $table->string('fanza_id')->nullable()->after('tmdb_id');
            $table->string('mgstage_id')->nullable()->after('fanza_id');
            $table->string('dmm_id')->nullable()->after('mgstage_id');
            $table->string('fc2_id')->nullable()->after('dmm_id');
            $table->string('eicbook_id')->nullable()->after('fc2_id');
            $table->string('wikidata_id')->nullable()->after('eicbook_id');
            $table->string('google_id')->nullable()->after('wikidata_id');
        });

        Schema::dropIfExists('links');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('imdb_id');
            $table->dropColumn('tmdb_id');
            $table->dropColumn('fanza_id');
            $table->dropColumn('mgstage_id');
            $table->dropColumn('dmm_id');
            $table->dropColumn('fc2_id');
            $table->dropColumn('eicbook_id');
            $table->dropColumn('wikidata_id');
            $table->dropColumn('google_id');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('twitter_id');
            $table->dropColumn('instagram_id');
            $table->dropColumn('tiktok_id');
            $table->dropColumn('ameblo_id');
            $table->dropColumn('wikidata_id');
            $table->dropColumn('youtube_id');
            $table->dropColumn('google_id');
            $table->dropColumn('imdb_id');
            $table->dropColumn('fanza_id');
            $table->dropColumn('tmdb_id');
            $table->dropColumn('line_blog_id');
            $table->dropColumn('onlyfans_id');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->boolean('breast_implants')->default(false)->after('cup_size');
        });
    }
};
