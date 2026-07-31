<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress')->default(0)->after('status');
        });

        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('target_date');
            $table->unsignedTinyInteger('weight');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('project_milestone_id')->nullable()->constrained('project_milestones')->cascadeOnUpdate()->nullOnDelete();
            $table->string('title');
            $table->string('assignee')->nullable();
            $table->string('priority');
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->nullableMorphs('subject');
            $table->string('event');
            $table->string('description');
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_activities');
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('project_milestones');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
