<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_disease', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disease_id')->constrained()->cascadeOnDelete();
            $table->date('diagnosed_at')->nullable();
            $table->enum('status', ['confirmed', 'suspected', 'resolved'])->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'disease_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_disease');
    }
};
