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
            $table->boolean('is_vr')->default(false)->after('length')->index();
            $table->boolean('is_3d')->default(false)->after('is_vr')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('is_vr');
            $table->dropColumn('is_3d');
        });
    }
};
