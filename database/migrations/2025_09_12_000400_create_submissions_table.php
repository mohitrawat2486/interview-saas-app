<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('submissions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $t->foreignId('candidate_id')->constrained('users')->cascadeOnDelete();
            $t->timestamp('started_at')->nullable();
            $t->timestamp('submitted_at')->nullable();
            $t->string('status')->default('in_progress');
            $t->timestamps();
            $t->unique(['interview_id','candidate_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('submissions');
    }
};
