<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lesions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index(); // Tipo general de la lesión
            $table->string('cause')->nullable(); // Causa específica
            $table->date('event_date')->index();
            $table->string('severity')->index(); // leve|moderada|grave
            $table->string('body_part')->index();
            $table->text('description')->nullable();
            $table->boolean('requires_hospitalization')->default(false)->index();
            $table->string('origin')->index(); // domestica|laboral|deportiva|transito|otra
            $table->string('treatment_status')->default('activa')->index(); // activa|resuelta
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesions');
    }
};
