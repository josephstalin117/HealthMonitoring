<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfilesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('profiles', function (Blueprint $table) {
            //
            $table->timestamp('birth')->after('telephone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('profiles', function (Blueprint $table) {
            //
            $table->dropColumn('birth');
        });
    }
}
