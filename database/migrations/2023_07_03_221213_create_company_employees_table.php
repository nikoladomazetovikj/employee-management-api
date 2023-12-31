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
        Schema::create('company_employees', function (Blueprint $table) {
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->integer('vacation_days')->nullable();

            $table->unique(['user_id', 'company_id']);
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_employees');
    }
};
