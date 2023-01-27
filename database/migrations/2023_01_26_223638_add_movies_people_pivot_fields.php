<?php

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
        Schema::table('movie_person', function (Blueprint $table) {
            $table->tinyInteger('age')->nullable();
            $table->index('age');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_person', function (Blueprint $table) {
            $table->dropIndex('age');
            $table->dropColumn('age');
        });
    }
};
