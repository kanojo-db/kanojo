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
        Schema::table('movies', function (Blueprint $table): void {
            $table->json('locked_columns')->default('[]')->after('google_id');
        });

        Schema::table('people', function (Blueprint $table): void {
            $table->json('locked_columns')->default('[]')->after('onlyfans_id');
        });

        Schema::table('series', function (Blueprint $table): void {
            $table->json('locked_columns')->default('[]')->after('slug');
        });

        Schema::table('studios', function (Blueprint $table): void {
            $table->json('locked_columns')->default('[]')->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table): void {
            $table->dropColumn('locked_columns');
        });

        Schema::table('people', function (Blueprint $table): void {
            $table->dropColumn('locked_columns');
        });

        Schema::table('series', function (Blueprint $table): void {
            $table->dropColumn('locked_columns');
        });

        Schema::table('studios', function (Blueprint $table): void {
            $table->dropColumn('locked_columns');
        });
    }
};
