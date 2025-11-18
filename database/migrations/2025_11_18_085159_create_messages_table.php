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
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->enum('direction', ['inbound', 'outbound']);
            $table->enum('sender_type', ['customer', 'agent', 'bot']);
            $table->string('sender_id')->nullable();
            $table->json('content');
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamp('read_at')->nullable();

            $table->index('conversation_id');
            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
