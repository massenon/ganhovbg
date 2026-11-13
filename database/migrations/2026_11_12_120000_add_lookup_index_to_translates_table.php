<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * La fonction lang() cherche chaque libellé par (lang_code, group_langname, key) dans une table de plus de 3 millions
 * de lignes SANS index : chaque appel balaie toute la table, ce qui rend les pages d'espace client très lentes.
 * Index à préfixe (key est un TEXT). Sur la base de production, créer l'index hors heures de pointe (quelques dizaines de secondes).
 */
class AddLookupIndexToTranslatesTable extends Migration
{
    public function up()
    {
        $exists = DB::select("SHOW INDEX FROM translates WHERE Key_name = 'translates_lookup'");
        if (empty($exists)) {
            DB::statement('CREATE INDEX translates_lookup ON translates (lang_code, group_langname(64), `key`(191))');
        }
    }

    public function down()
    {
        DB::statement('DROP INDEX translates_lookup ON translates');
    }
}
