<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Dédoublonne la table `translates`.
 *
 * Cause : l'ancien helper lang() réinsérait une ligne à CHAQUE appel quand la langue par défaut (`en`) n'existait pas
 * dans la table `languages` (seule `fr` est installée) : plusieurs millions de lignes identiques, pages de plus en plus lentes.
 * Le nouveau helper est idempotent ; cette migration supprime les doublons existants.
 *
 * Règle de conservation : une ligne par (lang_code, group_langname, key) — la plus récente qui a une valeur,
 * sinon la plus ancienne. L'ancienne table est CONSERVÉE sous le nom `translates_old` (à supprimer à la main
 * quand tout est vérifié : DROP TABLE translates_old). Idempotente : ne fait rien s'il n'y a plus de doublons.
 * Avant la production : sauvegarde de la base, puis lancer hors heures de pointe (plusieurs minutes pour ~3,5 M de lignes).
 */
class DedupeTranslatesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('translates')) {
            return;
        }
        $dup = DB::selectOne('SELECT 1 AS d FROM translates GROUP BY lang_code, group_langname, `key` HAVING COUNT(*) > 1 LIMIT 1');
        if (!$dup) {
            return;
        }

        DB::statement('DROP TABLE IF EXISTS translates_dedup');
        DB::statement('CREATE TABLE translates_dedup LIKE translates');
        DB::statement("INSERT INTO translates_dedup
            SELECT t.* FROM translates t
            JOIN (
                SELECT COALESCE(MAX(CASE WHEN value IS NOT NULL AND value <> '' THEN id END), MIN(id)) AS keep_id
                FROM translates GROUP BY lang_code, group_langname, `key`
            ) k ON k.keep_id = t.id");
        DB::statement('DROP TABLE IF EXISTS translates_old');
        DB::statement('RENAME TABLE translates TO translates_old, translates_dedup TO translates');
    }

    public function down()
    {
        if (Schema::hasTable('translates_old')) {
            DB::statement('DROP TABLE IF EXISTS translates_new');
            DB::statement('RENAME TABLE translates TO translates_new, translates_old TO translates');
            DB::statement('DROP TABLE translates_new');
        }
    }
}
