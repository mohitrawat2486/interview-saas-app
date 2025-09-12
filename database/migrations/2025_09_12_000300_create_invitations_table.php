<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invitations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $t->string('candidate_email');
            $t->string('token')->unique();
            $t->timestamp('expires_at')->nullable();
            $t->foreignId('candidate_user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('status')->default('invited');
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('invitations');
    }
};
