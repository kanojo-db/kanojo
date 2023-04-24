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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
        });

        // Add a country_id column to the people table and remove the country column.
        Schema::table('people', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('birthdate')->constrained();
            $table->dropColumn('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');

        // Add a country column to the people table and remove the country_id column.
        Schema::table('people', function (Blueprint $table) {
            $table->string('country')->nullable()->after('birthdate');
            $table->dropForeign(['country_id']);
        });
    }
};
