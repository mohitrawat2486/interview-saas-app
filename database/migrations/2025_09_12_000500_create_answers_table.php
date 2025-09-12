<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('answers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $t->foreignId('question_id')->constrained()->cascadeOnDelete();
            $t->string('video_path');
            $t->unsignedInteger('duration_seconds')->nullable();
            $t->unsignedInteger('retake_number')->default(1);
            $t->timestamp('recorded_at')->nullable();
            $t->timestamps();
            $t->index(['submission_id','question_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('answers');
    }
};
