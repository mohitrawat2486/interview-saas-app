<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('review_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('review_id')->constrained()->cascadeOnDelete();
            $t->foreignId('question_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('score')->nullable();
            $t->text('comment')->nullable();
            $t->timestamps();
            $t->unique(['review_id','question_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('review_items');
    }
};
