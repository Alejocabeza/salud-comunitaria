<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icd_code')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->boolean('contagious')->default(false);
            $table->enum('severity', ['mild', 'moderate', 'severe', 'critical'])->default('moderate');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diseases');
    }
};
