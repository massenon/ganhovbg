<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Champs éditoriaux des actualités : chapô, légende et crédit de l'image à la une, source, auteur.
 * Tous facultatifs : les anciens articles continuent de s'afficher comme avant.
 */
class AddEditorialColumnsToArticlesTable extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'chapo')) {
                $table->text('chapo')->nullable();
            }
            if (!Schema::hasColumn('articles', 'image_caption')) {
                $table->string('image_caption', 255)->nullable();
            }
            if (!Schema::hasColumn('articles', 'image_credit')) {
                $table->string('image_credit', 255)->nullable();
            }
            if (!Schema::hasColumn('articles', 'source')) {
                $table->string('source', 255)->nullable();
            }
            if (!Schema::hasColumn('articles', 'author_name')) {
                $table->string('author_name', 120)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            foreach (['chapo', 'image_caption', 'image_credit', 'source', 'author_name'] as $c) {
                if (Schema::hasColumn('articles', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
}
