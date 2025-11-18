<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->nullable()->constrained('businesses')->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category')->default('marketing'); // marketing, transactional, notification

            $table->string('subject');
            $table->text('content'); // HTML content
            $table->text('plain_text')->nullable(); // Plain text version

            $table->json('available_variables')->nullable(); // List of template variables
            $table->json('default_values')->nullable(); // Default values for variables

            $table->boolean('is_predefined')->default(false); // System templates
            $table->boolean('is_active')->default(true);

            $table->string('preview_image')->nullable(); // Screenshot/preview

            $table->timestamps();
            $table->softDeletes();

            $table->index('business_id');
            $table->index('category');
            $table->index('is_predefined');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
