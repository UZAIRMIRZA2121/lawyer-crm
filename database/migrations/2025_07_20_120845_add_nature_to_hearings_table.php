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
        Schema::table('hearings', function (Blueprint $table) {
            $table->string('nature')->nullable()->after('id'); // Adjust 'after' as needed
        });
    }

    public function down()
    {
        Schema::table('hearings', function (Blueprint $table) {
            $table->dropColumn('nature');
        });
    }
};
