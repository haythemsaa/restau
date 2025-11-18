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
        Schema::create('campaign_sends', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->constrained('email_campaigns')->onDelete('cascade');
            $table->foreignUuid('customer_id')->constrained('customers')->onDelete('cascade');

            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();

            $table->enum('status', ['pending', 'sent', 'delivered', 'bounced', 'failed'])->default('pending');

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();

            $table->integer('open_count')->default(0);
            $table->integer('click_count')->default(0);

            $table->string('provider_message_id')->nullable(); // From email provider
            $table->text('error_message')->nullable(); // For failed sends

            $table->json('metadata')->nullable(); // Additional tracking data

            $table->timestamps();

            $table->index('campaign_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_sends');
    }
};
