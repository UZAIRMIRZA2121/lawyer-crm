<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHearingsTable extends Migration
{
    public function up()
    {
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->string('judge_name');
            $table->text('judge_remarks')->nullable();
            $table->text('my_remarks')->nullable();
            $table->dateTime('next_hearing')->nullable();
            $table->enum('priority', ['important', 'normal'])->default('normal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hearings');
    }
}
