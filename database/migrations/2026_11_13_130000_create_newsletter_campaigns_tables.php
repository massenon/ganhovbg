<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Envois de newsletter : campagnes (historique) et remises par abonné·e (anti double envoi + reprise). Aucune adresse n'est dupliquée. */
class CreateNewsletterCampaignsTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('newsletter_campaigns')) {
            Schema::create('newsletter_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('subject', 190);
                $table->text('body');
                $table->string('button_label', 60)->nullable();
                $table->string('button_url', 255)->nullable();
                $table->string('status', 12)->default('sending'); // sending | sent | aborted
                $table->unsignedInteger('total')->default(0);
                $table->unsignedInteger('sent_count')->default(0);
                $table->unsignedInteger('failed_count')->default(0);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamp('locked_until')->nullable();
                $table->text('last_error')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('finished_at')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('newsletter_deliveries')) {
            Schema::create('newsletter_deliveries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('campaign_id');
                $table->unsignedBigInteger('subscriber_id');
                $table->string('status', 8)->default('queued'); // queued | sent | failed
                $table->timestamp('sent_at')->nullable();
                $table->unique(['campaign_id', 'subscriber_id']);
                $table->index(['campaign_id', 'status']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('newsletter_deliveries');
        Schema::dropIfExists('newsletter_campaigns');
    }
}
