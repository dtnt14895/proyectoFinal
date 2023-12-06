<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();        
            $table->string('name');
            $table->string('lastname');
            $table->string('phone');
            $table->date('born');
            $table->timestamps();
            $table->unsignedBigInteger('create_by')->nullable()->default(null);
            $table->unsignedBigInteger('update_by')->nullable()->default(null);
            $table->tinyInteger('status')->default(1);  
            $table->foreign('create_by')->references('id')->on('users');
            $table->foreign('update_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
