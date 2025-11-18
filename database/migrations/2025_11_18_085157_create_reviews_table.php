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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->constrained('businesses')->onDelete('cascade');
            $table->string('platform', 50);
            $table->string('platform_review_id')->nullable();
            $table->string('author_name')->nullable();
            $table->decimal('rating', 2, 1);
            $table->text('text')->nullable();
            $table->text('response_text')->nullable();
            $table->timestamp('response_date')->nullable();
            $table->decimal('sentiment_score', 3, 2)->nullable();
            $table->json('categories')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'platform']);
            $table->index('rating');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
