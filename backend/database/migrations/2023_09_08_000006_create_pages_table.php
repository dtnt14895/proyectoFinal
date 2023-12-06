<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->string('name')->unique();
            $table->string('description');
            $table->string('icon');
            $table->string('type');
            $table->timestamps();
            $table->unsignedBigInteger('create_by');
            $table->unsignedBigInteger('update_by');
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
        Schema::dropIfExists('pages');
    }
};
