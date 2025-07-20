<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('case_models', function (Blueprint $table) {
        $table->string('case_nature')->nullable()->after('case_title'); // adjust 'case_title' as needed
    });
}

public function down()
{
    Schema::table('case_models', function (Blueprint $table) {
        $table->dropColumn('case_nature');
    });
}
};
