<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('blogs', 'excerpt')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('excerpt', 500)->nullable()->after('content');
            });
        }
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('excerpt');
        });
    }
};