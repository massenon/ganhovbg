<?php

namespace App\Http\Controllers;

use App\Models\Articles\Article;
use App\Models\FaqCategory;
use App\Models\Pages;
use App\Models\Ticket\Category;
use Illuminate\Support\Facades\Schema;

/**
 * robots.txt et sitemap.xml dynamiques. Les URLs absolues derivent de config('app.url').
 */
class SeoController extends Controller
{
    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /administration',
            'Disallow: /admin',
            'Disallow: /espace-client',
            'Disallow: /customer',
            'Disallow: /api',
            'Disallow: /install',
            'Disallow: /search',
            'Disallow: /guest/ticket',
            'Disallow: /guest/ticket-view',
            'Disallow: /guest/ticketdetails',
            'Disallow: /uploads/guestticket',
            'Disallow: /uploads/ticket',
            'Disallow: /uploads/comment',
            'Disallow: /uploads/emailtoticket',
            'Disallow: /uploads/profile',
            '',
            'Sitemap: ' . gn_url('sitemap.xml'),
            '',
        ];
        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap()
    {
        $urls = [];
        $add = function ($path, $lastmod = null, $freq = 'monthly', $prio = '0.5') use (&$urls) {
            $urls[] = [
                'loc' => gn_url($path),
                'lastmod' => $lastmod ? \Carbon\Carbon::parse($lastmod)->toAtomString() : null,
                'changefreq' => $freq,
                'priority' => $prio,
            ];
        };

        $add('/', now(), 'weekly', '1.0');
        if (setting('GUEST_TICKET') == 'yes') {
            $add('guest/openticket', null, 'yearly', '0.8');
        }
        if (\App\Models\AssistantSetting::flag('enabled')) {
            $add('assistant', null, 'monthly', '0.7');
        }
        if (setting('FAQ_ENABLE') == 'yes') {
            $add('faq', optional(\App\Models\FAQ::where('status', '1')->latest('updated_at')->first())->updated_at, 'monthly', '0.8');
            foreach (FaqCategory::where('status', '1')->get() as $c) {
                $add('faq/' . $c->id, $c->updated_at, 'monthly', '0.6');
            }
        }
        if (setting('KNOWLEDGE_ENABLE') == 'yes') {
            $add('knowledge', optional(Article::where('status', 'Published')->latest('updated_at')->first())->updated_at, 'weekly', '0.8');
            foreach (Article::where('status', 'Published')->where(function ($q) { $q->whereNull('privatemode')->orWhere('privatemode', 0); })->get() as $a) {
                $add('article/' . ($a->articleslug ?: $a->id), $a->updated_at, 'monthly', '0.7');
            }
            foreach (Category::whereIn('display', ['knowledge', 'both'])->where('status', '1')->get() as $c) {
                $add('category/' . ($c->categoryslug ?: $c->id), $c->updated_at, 'monthly', '0.5');
            }
        }
        if (setting('CONTACT_ENABLE') == 'yes') {
            $add('contact-us', null, 'yearly', '0.6');
        }
        foreach (Pages::where('status', '1')->get() as $p) {
            $add('page/' . $p->pageslug, $p->updated_at, 'yearly', '0.4');
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n    <loc>" . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
            if ($u['lastmod']) { $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n"; }
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n    <priority>" . $u['priority'] . "</priority>\n  </url>\n";
        }
        $xml .= '</urlset>' . "\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
