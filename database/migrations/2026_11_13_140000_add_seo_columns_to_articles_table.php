<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** SEO éditorial des actualités : titre SEO, méta-description et FAQ (JSON : [[question, réponse], ...]). */
class AddSeoColumnsToArticlesTable extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'meta_title')) { $table->string('meta_title', 120)->nullable(); }
            if (!Schema::hasColumn('articles', 'meta_description')) { $table->string('meta_description', 320)->nullable(); }
            if (!Schema::hasColumn('articles', 'faq')) { $table->text('faq')->nullable(); }
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            foreach (['meta_title', 'meta_description', 'faq'] as $c) {
                if (Schema::hasColumn('articles', $c)) { $table->dropColumn($c); }
            }
        });
    }
}
