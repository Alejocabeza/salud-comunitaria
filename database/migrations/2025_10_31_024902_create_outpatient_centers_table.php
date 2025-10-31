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
        Schema::create('outpatient_centers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('responsible');
            $table->string('address');
            $table->integer('capacity');
            $table->integer('current_occupancy')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outpatient_centers');
    }
};
