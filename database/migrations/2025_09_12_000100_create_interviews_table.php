<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('interviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $t->string('title');
            $t->text('description')->nullable();
            $t->json('settings')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('interviews');
    }
};
