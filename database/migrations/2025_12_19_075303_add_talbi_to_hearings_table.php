<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('hearings', function (Blueprint $table) {
            $table->enum('talbi', [
                'Notice',
                'Warrant',
                'Newspaper',
                'AD/Registry'
            ])->nullable()->after('nature');
        });
    }

    public function down()
    {
        Schema::table('hearings', function (Blueprint $table) {
            $table->dropColumn('talbi');
        });
    }
};
