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
        Schema::create('inquires', function (Blueprint $table) {
            $table->ulid('inquire_id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('status_id')->nullable()->unsigned();
            $table->foreign('status_id')->references('id')->on('statuses')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('type')->nullable()->unsigned();
            $table->foreign('type')->references('id')->on('inquire_types')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->date('start');
            $table->date('end');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status_id', 'type', 'start']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquires');
    }
};
