<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->foreignId('uploaded_by_user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('original_filename');
            $table->string('stored_path');
            $table->string('disk')->default('local');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->timestamps();

            $table->index(['disk', 'stored_path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
