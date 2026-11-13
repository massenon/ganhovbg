<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Garde CI : le poids des assets de premier chargement ne doit pas deriver.
 * Echoue si un fichier JS depasse 300 Ko ou un fichier CSS 100 Ko (non compresse),
 * ou si le cumul charge par une page publique depasse les memes plafonds.
 */
class PerformanceAssetsTest extends TestCase
{
    private const MAX_JS = 300 * 1024;
    private const MAX_CSS = 100 * 1024;

    private function racine(): string
    {
        return dirname(__DIR__, 2) . '/public/assets/ganho';
    }

    public function test_aucun_fichier_ganho_ne_depasse_le_plafond(): void
    {
        $trop = [];
        foreach (['css' => self::MAX_CSS, 'js' => self::MAX_JS] as $ext => $max) {
            foreach (glob($this->racine() . "/$ext/*.$ext") as $f) {
                if (filesize($f) > $max) {
                    $trop[] = basename($f) . ' = ' . round(filesize($f) / 1024) . ' Ko (max ' . round($max / 1024) . ')';
                }
            }
        }
        $this->assertSame([], $trop, "Assets trop lourds :\n" . implode("\n", $trop));
    }

    public function test_cumul_premier_chargement_public_et_application(): void
    {
        $css = ['ganho.css', 'gn-assistant.css'];
        $js = ['ganho-home.js', 'gn-assistant.js'];
        $sum = fn (string $dir, array $files) => array_sum(array_map(fn ($f) => filesize($this->racine() . "/$dir/$f"), $files));
        $this->assertLessThanOrEqual(self::MAX_CSS, $sum('css', $css), 'CSS du premier chargement public > 100 Ko');
        $this->assertLessThanOrEqual(self::MAX_JS, $sum('js', $js), 'JS du premier chargement public > 300 Ko');
        $this->assertLessThanOrEqual(self::MAX_CSS, filesize($this->racine() . '/css/gn-app.css'), 'gn-app.css > 100 Ko');
    }

    public function test_service_worker_et_manifeste_presents(): void
    {
        $public = dirname(__DIR__, 2) . '/public';
        $this->assertFileExists($public . '/sw.js');
        $manifeste = json_decode(file_get_contents($public . '/manifest.webmanifest'), true);
        $this->assertSame('standalone', $manifeste['display']);
        $this->assertNotEmpty($manifeste['icons']);
        $this->assertStringContainsString('/assets/ganho/', file_get_contents($public . '/sw.js'), 'sw.js doit servir /assets/ganho/ cache-first');
    }
}
