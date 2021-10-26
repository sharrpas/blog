<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditIsConfirmInPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('is_config');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->enum('status', ['accepted', 'rejected', 'pending'])->after('text')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_config')->after('text')->default(false);
        });

    }
}
