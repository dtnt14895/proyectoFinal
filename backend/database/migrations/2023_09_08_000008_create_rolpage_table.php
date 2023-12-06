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
        Schema::create('rol_page', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('enlaced_to')->nullable()->default(null);
            $table->unsignedBigInteger('page_id')->nullable()->default(null);
            $table->unsignedBigInteger('rol_id');
            $table->integer('order');
            $table->timestamps();
            $table->unsignedBigInteger('create_by');
            $table->unsignedBigInteger('update_by');
            $table->tinyInteger('status')->default(1);  
            $table->foreign('create_by')->references('id')->on('users');
            $table->foreign('update_by')->references('id')->on('users');
            $table->foreign('page_id')->references('id')->on('pages');
            $table->foreign('rol_id')->references('id')->on('rols');
            $table->foreign('enlaced_to')->references('id')->on('rol_page');
            // $table->index(['page_id', 'rol_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_page');
    }
};
