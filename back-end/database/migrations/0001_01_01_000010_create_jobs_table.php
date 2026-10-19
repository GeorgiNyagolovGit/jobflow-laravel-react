<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('job_type_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('salary_currency', 3)->default('EUR');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('location');
            $table->index('is_remote');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};