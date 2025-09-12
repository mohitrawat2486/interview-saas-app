<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $t->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $t->unsignedTinyInteger('overall_score')->nullable();
            $t->text('overall_comment')->nullable();
            $t->timestamps();
            $t->unique(['submission_id','reviewer_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};
