<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('offer_badge')->nullable()->after('discount');
            $table->string('offer_type')->nullable()->after('offer_badge');
            $table->datetime('offer_start_date')->nullable()->after('offer_type');
            $table->datetime('offer_end_date')->nullable()->after('offer_start_date');
            $table->boolean('offer_status')->default(true)->after('offer_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'offer_badge', 'offer_type',
                'offer_start_date', 'offer_end_date', 'offer_status'
            ]);
        });
    }
};