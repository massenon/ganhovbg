<?php

namespace Database\Seeders;

use App\Models\Articles\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 14 actualités tirées des visuels Pool Com BESYP (dossier blogs-images), avec SEO complet :
 * titre SEO (meta_title), méta-description, slug, chapô, H2/H3, FAQ (JSON-LD FAQPage), légende d'image.
 * Idempotent (mise à jour par slug). Prérequis : migration 2026_11_13_140000 + NewsArticlesSeeder (catégories).
 * Lancer : php artisan db:seed --class=NewsPoolComSeeder
 */
class NewsPoolComSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $cats = [];
        foreach ([['Pool Com', 'pool-com'], ['Santé et droits', 'sante-et-droits']] as $c) {
            $id = DB::table('categories')->where('categoryslug', $c[1])->value('id');
            if (!$id) {
                $id = DB::table('categories')->insertGetId(['name' => $c[0], 'display' => 'knowledge', 'categoryslug' => $c[1], 'priority' => 0, 'status' => '1', 'created_at' => $now, 'updated_at' => $now]);
            }
            $cats[$c[1]] = $id;
        }
        $n = 0;
        foreach ([1, 2, 3] as $part) {
            foreach (require __DIR__ . '/data/news_part' . $part . '.php' as $a) {
                $data = [
                    'title' => $a['title'],
                    'articleslug' => $a['slug'],
                    'category_id' => $cats[$a['cat']],
                    'tags' => $a['tags'],
                    'status' => 'Published', 'privatemode' => 0,
                    'featureimage' => $a['image'],
                    'chapo' => $a['chapo'],
                    'image_caption' => $a['caption'],
                    'image_credit' => 'Pool Com BESYP (affiche)',
                    'source' => 'BESYP, Pool Com',
                    'author_name' => 'Équipe GANXÔ',
                    'meta_title' => $a['meta_title'],
                    'meta_description' => $a['meta_description'],
                    'faq' => json_encode($a['faq'], JSON_UNESCAPED_UNICODE),
                    'message' => $a['body'],
                ];
                $existing = Article::where('articleslug', $a['slug'])->first();
                if ($existing) {
                    $existing->fill($data)->save();
                } else {
                    $m = new Article();
                    $m->fill($data);
                    $m->views = 0;
                    $m->created_at = $a['date'];
                    $m->updated_at = $a['date'];
                    $m->save();
                }
                $n++;
            }
        }
        $this->command && $this->command->info($n . ' actualités publiées.');
    }
}
