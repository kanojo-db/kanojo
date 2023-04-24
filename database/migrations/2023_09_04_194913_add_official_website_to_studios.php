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
            $table->string('official_website')->nullable()->after('slug');
            $table->string('corporate_number')->nullable()->after('official_website');
            $table->string('twitter_id')->nullable()->after('corporate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn('official_website');
            $table->dropColumn('corporate_number');
            $table->dropColumn('twitter_id');
        });
    }
};
