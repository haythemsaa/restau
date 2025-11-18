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
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignUuid('template_id')->nullable()->constrained('email_templates')->onDelete('set null');

            $table->string('name');
            $table->string('subject');
            $table->text('content');
            $table->json('variables')->nullable(); // Template variables

            // Targeting
            $table->string('target_type')->default('all'); // all, segment, tier, custom
            $table->json('target_criteria')->nullable(); // For custom targeting
            $table->json('segment_ids')->nullable(); // Selected segments
            $table->json('tier_filters')->nullable(); // VIP, regular, super_vip

            // Scheduling
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Statistics
            $table->integer('recipients_count')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('delivered_count')->default(0);
            $table->integer('opened_count')->default(0);
            $table->integer('clicked_count')->default(0);
            $table->integer('bounced_count')->default(0);
            $table->integer('failed_count')->default(0);

            // Settings
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('reply_to')->nullable();
            $table->boolean('track_opens')->default(true);
            $table->boolean('track_clicks')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('business_id');
            $table->index('status');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};
