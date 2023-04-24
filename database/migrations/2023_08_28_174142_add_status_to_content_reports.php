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
        Schema::table('content_reports', function (Blueprint $table) {
            // Replace the resolved column with a status column, which will take an enum value.
            $table->dropColumn('resolved');
            $table->enum('status', ['open', 'resolved', 'rejected', 'invalid', 'in_progress'])->default('open')->after('public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_reports', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->boolean('resolved')->default(false)->after('public');
        });
    }
};
