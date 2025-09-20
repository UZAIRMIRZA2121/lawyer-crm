<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('case_models', function (Blueprint $table) {
        $table->string('sub_status')->nullable()->after('status'); // ✅ added after status
    });
}

public function down()
{
    Schema::table('case_models', function (Blueprint $table) {
        $table->dropColumn('sub_status');
    });
}
};
