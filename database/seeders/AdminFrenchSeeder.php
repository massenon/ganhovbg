<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Administration : valeurs françaises des chaînes lang() qui s'affichaient encore en anglais
 * (réglages, tableaux, formulaires, alertes, notifications, rôles…). Données : data_admin_fr.json (groupe, clé, valeur).
 * Idempotent : met à jour TOUTES les lignes fr de la clé et du groupe (lang() crée des doublons vides), sinon insère.
 * Lancer : php artisan db:seed --class=AdminFrenchSeeder
 */
class AdminFrenchSeeder extends Seeder
{
    public function run()
    {
        $rows = json_decode(file_get_contents(__DIR__ . '/data_admin_fr.json'), true) ?: [];
        $now = now();
        $done = 0;
        foreach ($rows as $r) {
            $group = strtolower($r['g']);
            $n = DB::table('translates')->where('lang_code', 'fr')->where('key', $r['k'])->where('group_langname', $group)
                ->update(['value' => $r['v'], 'updated_at' => $now]);
            if (!$n) {
                DB::table('translates')->insert(['lang_code' => 'fr', 'group_langname' => $group, 'key' => $r['k'], 'value' => $r['v'], 'created_at' => $now, 'updated_at' => $now]);
            }
            $done++;
        }

        // Messages d'alerte des contrôleurs : on ne remplace jamais une traduction existante non vide.
        $alerts = array_merge(json_decode(file_get_contents(__DIR__ . '/data_admin_alerts_fr.json'), true) ?: [], json_decode(file_get_contents(__DIR__ . '/data_admin_module_fr.json'), true) ?: []);
        foreach ($alerts as $r) {
            $group = strtolower($r['g']);
            $q = DB::table('translates')->where('lang_code', 'fr')->where('key', $r['k'])->where('group_langname', $group);
            if ((clone $q)->whereNotNull('value')->where('value', '<>', '')->exists()) { continue; }
            $n = (clone $q)->update(['value' => $r['v'], 'updated_at' => $now]);
            if (!$n) {
                DB::table('translates')->insert(['lang_code' => 'fr', 'group_langname' => $group, 'key' => $r['k'], 'value' => $r['v'], 'created_at' => $now, 'updated_at' => $now]);
            }
            $done++;
        }

        // Descriptions des modèles d'e-mail (affichées dans l'administration) : traduites seulement si elles sont encore celles d'origine.
        $titles = json_decode(file_get_contents(__DIR__ . '/data_email_titles_fr.json'), true) ?: [];
        foreach ($titles as $code => $fr) {
            DB::table('email_templates')->where('code', $code)->where('title', 'not like', '%’%')->where('title', 'regexp', '^[A-Za-z]')->update(['title' => $fr]);
        }

        // Corrections de libellés existants mal traduits.
        foreach (['Sl.No' => 'N°'] as $k => $v) {
            DB::table('translates')->where('lang_code', 'fr')->where('group_langname', 'general')->where('key', $k)->update(['value' => $v, 'updated_at' => $now]);
        }
        $this->command->info($done . ' chaînes françaises appliquées à l’administration.');
    }
}
