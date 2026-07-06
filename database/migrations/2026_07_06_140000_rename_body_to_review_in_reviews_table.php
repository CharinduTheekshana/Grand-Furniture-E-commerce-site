<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('reviews', 'body') && !Schema::hasColumn('reviews', 'review')) {
            DB::statement('ALTER TABLE reviews CHANGE body review TEXT NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reviews', 'review') && !Schema::hasColumn('reviews', 'body')) {
            DB::statement('ALTER TABLE reviews CHANGE review body TEXT NOT NULL');
        }
    }
};
