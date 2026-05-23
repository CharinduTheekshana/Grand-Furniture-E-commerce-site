<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nickname');
            $table->string('summary');
            $table->text('body');
            $table->unsignedTinyInteger('quality')->default(3);
            $table->unsignedTinyInteger('price')->default(3);
            $table->unsignedTinyInteger('value')->default(3);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
};