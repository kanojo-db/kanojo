<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsMetadataTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_settings_metadata', function (Blueprint $table): void {
            $table->id();

            $table->string('name')->unique();
            $table->string('type');
            $table->string('default')->nullable();
            $table->boolean('is_enabled')->default(true);

            $table->string('group')->default('default');
            $table->string('bag')->default(config('laraset.default', 'users'));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings_metadata');
    }
}
