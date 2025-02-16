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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('form_id')->nullable()->constrained()->onDelete('set null');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('year_id')->constrained();
            $table->boolean('is_sent')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};