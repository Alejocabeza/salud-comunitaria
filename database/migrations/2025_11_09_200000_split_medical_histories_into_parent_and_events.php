<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('medical_histories') && ! Schema::hasTable('medical_history_events')) {
            Schema::rename('medical_histories', 'medical_history_events');
        }

        Schema::table('medical_history_events', function (Blueprint $table) {
            if (! Schema::hasColumn('medical_history_events', 'medical_history_id')) {
                $table->unsignedBigInteger('medical_history_id')->nullable()->after('id');
            }
        });

        if (! Schema::hasTable('medical_histories')) {
            Schema::create('medical_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained()->unique();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        $events = DB::table('medical_history_events')->get();
        $grouped = $events->groupBy('patient_id');

        foreach ($grouped as $patientId => $rows) {
            // create parent only if not exists
            $parentId = DB::table('medical_histories')->where('patient_id', $patientId)->value('id');
            if (! $parentId) {
                $parentId = DB::table('medical_histories')->insertGetId([
                    'patient_id' => $patientId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($rows as $row) {
                DB::table('medical_history_events')
                    ->where('id', $row->id)
                    ->update(['medical_history_id' => $parentId]);
            }
        }

        Schema::table('medical_history_events', function (Blueprint $table) {
            if (! Schema::hasColumn('medical_history_events', 'medical_history_id')) {
                $table->unsignedBigInteger('medical_history_id')->nullable()->after('id');
            }
        });

        Schema::table('medical_history_events', function (Blueprint $table) {
            try {
                $table->foreign('medical_history_id')
                    ->references('id')
                    ->on('medical_histories')
                    ->cascadeOnDelete();
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('medical_history_events')) {
            Schema::table('medical_history_events', function (Blueprint $table) {
                if (! Schema::hasColumn('medical_history_events', 'patient_id')) {
                    $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete()->after('medical_history_id');
                }
            });

            $events = DB::table('medical_history_events')->get();
            foreach ($events as $ev) {
                $patientId = DB::table('medical_histories')->where('id', $ev->medical_history_id)->value('patient_id');
                DB::table('medical_history_events')->where('id', $ev->id)->update(['patient_id' => $patientId]);
            }
        }

        if (Schema::hasTable('medical_histories')) {
            Schema::dropIfExists('medical_histories');
        }

        if (Schema::hasTable('medical_history_events') && ! Schema::hasTable('medical_histories')) {
            Schema::rename('medical_history_events', 'medical_histories');
        }
    }
};
