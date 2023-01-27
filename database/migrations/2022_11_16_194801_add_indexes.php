<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->index('birthdate');
            $table->index('height');
            $table->index('bust');
            $table->index('waist');
            $table->index('hip');
            $table->index('cup_size');
            $table->index('country');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->index('product_code');
            $table->index('release_date');
            $table->index('studio_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropIndex('birthdate');
            $table->dropIndex('height');
            $table->dropIndex('bust');
            $table->dropIndex('waist');
            $table->dropIndex('hip');
            $table->dropIndex('cup_size');
            $table->dropIndex('country');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropIndex('product_code');
            $table->dropIndex('release_date');
            $table->dropIndex('studio_id');
        });
    }
};
