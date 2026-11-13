<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Abonné·es à la newsletter GANXÔ (formulaire du pied de page).
 * Ni IP, ni nom : e-mail, jeton de désinscription, date de consentement.
 */
class CreateNewsletterSubscribersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('newsletter_subscribers')) {
            Schema::create('newsletter_subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email', 190)->unique();
                $table->string('token', 64)->unique();
                $table->timestamp('consent_at')->nullable();
                $table->timestamp('unsubscribed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
}
