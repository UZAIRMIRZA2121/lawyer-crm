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
    Schema::table('notices', function (Blueprint $table) {
        $table->longText('notice_base64')->nullable()->after('notice');
    });
}

public function down()
{
    Schema::table('notices', function (Blueprint $table) {
        $table->dropColumn('notice_base64');
    });
}
};
