<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('case_models', function (Blueprint $table) {
            $table->decimal('commission_amount', 10, 2)->nullable()->after('amount');
        });
    }

    public function down()
    {
        Schema::table('case_models', function (Blueprint $table) {
            $table->dropColumn('commission_amount');
        });
    }
};
