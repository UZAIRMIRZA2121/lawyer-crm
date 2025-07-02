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
        Schema::create('case_models', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('case_title');
            $table->text('description')->nullable();
            $table->string('status')->default('open');
            $table->date('hearing_date')->nullable();
            $table->string('judge_name')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_models');
    }
};
