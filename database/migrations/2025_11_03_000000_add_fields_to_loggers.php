<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loggers', function (Blueprint $table) {
            if (! Schema::hasColumn('loggers', 'level')) {
                $table->string('level')->nullable()->after('action');
            }

            if (! Schema::hasColumn('loggers', 'message')) {
                $table->text('message')->nullable()->after('level');
            }

            if (! Schema::hasColumn('loggers', 'context')) {
                $table->json('context')->nullable()->after('message');
            }

            if (! Schema::hasColumn('loggers', 'trace')) {
                $table->text('trace')->nullable()->after('context');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loggers', function (Blueprint $table) {
            if (Schema::hasColumn('loggers', 'trace')) {
                $table->dropColumn('trace');
            }
            if (Schema::hasColumn('loggers', 'context')) {
                $table->dropColumn('context');
            }
            if (Schema::hasColumn('loggers', 'message')) {
                $table->dropColumn('message');
            }
            if (Schema::hasColumn('loggers', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};
