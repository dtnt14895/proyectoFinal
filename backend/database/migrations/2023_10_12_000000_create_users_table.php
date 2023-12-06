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
        Schema::table('users', function (Blueprint $table) {
          
            $table->foreign('create_by')->references('id')->on('users');
            $table->foreign('update_by')->references('id')->on('users');
            $table->foreign('person_id')->references('id')->on('persons');
            $table->foreign('rol_id')->references('id')->on('rols');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
