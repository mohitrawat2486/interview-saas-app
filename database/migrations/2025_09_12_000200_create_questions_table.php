<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('questions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $t->text('prompt');
            $t->unsignedInteger('order')->default(1);
            $t->unsignedInteger('time_limit_seconds')->default(120);
            $t->unsignedInteger('thinking_time_seconds')->default(5);
            $t->boolean('allow_retake')->default(true);
            $t->timestamps();
            $t->unique(['interview_id','order']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('questions');
    }
};
