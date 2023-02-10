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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->date('birthdate')->nullable();
            $table->date('career_start')->nullable();
            $table->date('career_end')->nullable();
            $table->unsignedTinyInteger('height')->nullable();
            $table->unsignedTinyInteger('bust')->nullable();
            $table->unsignedTinyInteger('waist')->nullable();
            $table->unsignedTinyInteger('hip')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('cup_size')->nullable();
            $table->boolean('breast_implants')->nullable();
            $table->string('country')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
