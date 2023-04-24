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
        Schema::create('movie_versions', function (Blueprint $table) {
            $table->id();
            $table->enum('format', [
                'DVD',
                'Blu-ray',
                'UHD Blu-ray',
                'Digital',
                'VHS',
                'Laserdisc',
                'UMD',
                'HD DVD',
            ]);
            $table->date('release_date')->nullable();
            $table->string('product_code')->nullable()->index();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_versions');
    }
};
