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
        Schema::create('customer_visits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('business_id')->constrained()->cascadeOnDelete();
            $table->timestamp('visited_at');
            $table->decimal('amount_spent', 10, 2)->nullable();
            $table->integer('party_size')->default(1);
            $table->json('items_ordered')->nullable(); // list of items/dishes ordered
            $table->integer('satisfaction_score')->nullable(); // 1-5 score
            $table->text('feedback')->nullable();
            $table->string('source')->nullable(); // walk-in, reservation, delivery
            $table->timestamps();

            $table->index(['customer_id', 'visited_at']);
            $table->index(['business_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_visits');
    }
};
