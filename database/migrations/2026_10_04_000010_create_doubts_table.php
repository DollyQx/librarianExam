<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doubts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('pending'); // pending, replied
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doubts');
    }
};
