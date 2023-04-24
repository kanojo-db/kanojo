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
        Schema::table('studios', function (Blueprint $table) {
            $table->string('official_website')->nullable()->after('founded_date');
            $table->string('wikidata_id')->nullable()->after('official_website');
            $table->string('google_id')->nullable()->after('wikidata_id');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->string('official_website')->nullable()->after('cup_size');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->string('official_website')->nullable()->after('title');
            $table->string('wikidata_id')->nullable()->after('official_website');
            $table->string('google_id')->nullable()->after('wikidata_id');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->string('official_website')->nullable()->after('is_3d');
            $table->dropColumn('product_code');
            $table->dropColumn('release_date');
            $table->dropColumn('barcode');
        });

        Schema::table('movie_versions', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('product_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn('official_website');
            $table->dropColumn('wikidata_id');
            $table->dropColumn('google_id');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('official_website');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('official_website');
            $table->dropColumn('wikidata_id');
            $table->dropColumn('google_id');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('official_website');
            $table->string('product_code')->after('title');
            $table->string('barcode')->nullable()->after('popularity');
            $table->date('release_date')->nullable()->after('barcode');
        });

        Schema::table('movie_versions', function (Blueprint $table) {
            $table->dropColumn('barcode');
        });
    }
};
