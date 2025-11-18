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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->json('preferences')->nullable(); // dietary restrictions, allergies, favorites
            $table->json('tags')->nullable(); // custom tags for segmentation
            $table->enum('tier', ['regular', 'vip', 'super_vip'])->default('regular');
            $table->decimal('lifetime_value', 10, 2)->default(0);
            $table->integer('visit_count')->default(0);
            $table->timestamp('last_visit_at')->nullable();
            $table->string('language', 5)->default('fr'); // fr, en, es, it
            $table->text('notes')->nullable(); // staff notes about customer
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('tier');
            $table->index('last_visit_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
