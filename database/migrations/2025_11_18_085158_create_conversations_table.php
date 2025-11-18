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
        Schema::create('conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->constrained('businesses')->onDelete('cascade');
            $table->enum('channel', ['facebook', 'instagram', 'whatsapp', 'google', 'email', 'sms']);
            $table->string('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->foreignUuid('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['new', 'open', 'pending', 'resolved', 'closed'])->default('new');
            $table->json('tags')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('business_id');
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
