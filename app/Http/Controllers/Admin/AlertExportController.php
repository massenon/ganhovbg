<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AlertReportService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date as XlDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Export Excel (.xlsx) et rapport statistique (PDF) des alertes VBG.
 * Accès : permission « Reports Export » ; périmètre limité aux cas de la personne connectée (sauf superadmin).
 * Journal : qui, quel format, combien de lignes (aucune donnée personnelle).
 */
class AlertExportController extends Controller
{
    private function guard()
    {
        $this->authorize('Reports Export');
    }

    /** Neutralise les formules : une valeur qui commence par = + - @ (ou tabulation) est préfixée par une apostrophe. */
    private function safe($v)
    {
        $v = (string) $v;
        return preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v;
    }

    public function xlsx(Request $request)
    {
        $this->guard();
        $user = Auth::user();
        $withCustomer = $user->can('Customers Access');
        $rows = AlertReportService::rows($user, $request, $withCustomer);

        $ss = new Spreadsheet();
        $ss->getProperties()->setCreator('GANXÔ Alerte VBG')->setTitle('Alertes VBG')->setSubject('Export des alertes')->setDescription('Données sensibles : ne pas diffuser.');
        $sh = $ss->getActiveSheet();
        $sh->setTitle('Alertes VBG');

        $head = ['N° d’alerte', 'Objet', 'Catégorie', 'Statut', 'Priorité', 'Créée le', 'Dernière mise à jour', 'Agent affecté'];
        if ($withCustomer) { $head[] = 'Victime / client·e'; }
        $head[] = 'Délai de 1re réponse (h)';
        $head[] = 'Délai de résolution (h)';
        foreach ($head as $i => $h) {
            $sh->setCellValueExplicitByColumnAndRow($i + 1, 1, $h, DataType::TYPE_STRING);
        }

        $dateFmt = 'dd/mm/yyyy hh:mm';
        $r = 2;
        foreach ($rows as $row) {
            $c = 1;
            $cells = [$row['numero'], $row['objet'], $row['categorie'], $row['statut'], $row['priorite']];
            foreach ($cells as $v) { $sh->setCellValueExplicitByColumnAndRow($c++, $r, $this->safe($v), DataType::TYPE_STRING); }
            foreach ([$row['cree'], $row['maj']] as $d) {
                if ($d) {
                    $sh->setCellValueExplicitByColumnAndRow($c, $r, XlDate::PHPToExcel(Carbon::parse($d)->setTimezone(config('app.timezone'))->toDateTime()), DataType::TYPE_NUMERIC);
                    $sh->getStyleByColumnAndRow($c, $r)->getNumberFormat()->setFormatCode($dateFmt);
                }
                $c++;
            }
            $sh->setCellValueExplicitByColumnAndRow($c++, $r, $this->safe($row['agent']), DataType::TYPE_STRING);
            if ($withCustomer) { $sh->setCellValueExplicitByColumnAndRow($c++, $r, $this->safe($row['victime']), DataType::TYPE_STRING); }
            foreach ([$row['h_reponse'], $row['h_resolution']] as $h) {
                if ($h !== null) { $sh->setCellValueExplicitByColumnAndRow($c, $r, $h, DataType::TYPE_NUMERIC); $sh->getStyleByColumnAndRow($c, $r)->getNumberFormat()->setFormatCode('0.0'); }
                $c++;
            }
            $r++;
        }

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($head));
        $last = max(2, $r - 1);
        $sh->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2B33C9']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
        $sh->getRowDimension(1)->setRowHeight(32);
        $sh->freezePane('A2');
        $sh->setAutoFilter("A1:{$lastCol}{$last}");
        $widths = [16, 44, 24, 14, 12, 18, 20, 30];
        if ($withCustomer) { $widths[] = 26; }
        $widths[] = 16; $widths[] = 16;
        foreach ($widths as $i => $w) {
            $sh->getColumnDimensionByColumn($i + 1)->setWidth($w);
        }
        $sh->getStyle("B2:B{$last}")->getAlignment()->setWrapText(true);
        $sh->getStyle("A2:{$lastCol}{$last}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        Log::info('export alertes xlsx', ['user_id' => $user->id, 'lignes' => count($rows)]);

        $name = 'alertes-vbg-' . now()->format('Y-m-d') . '.xlsx';
        return response()->streamDownload(function () use ($ss) {
            (new Xlsx($ss))->save('php://output');
        }, $name, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Cache-Control' => 'no-store']);
    }

    public function pdf(Request $request)
    {
        $this->guard();
        $user = Auth::user();
        $stats = AlertReportService::stats($user, $request);
        $logo = public_path('assets/ganho/img/logo-lettres-blanches-416w.png');

        $html = view('admin.alerts.report-pdf', [
            'stats' => $stats,
            'period' => AlertReportService::periodLabel($request),
            'scope' => $user->dashboard === 'Admin' ? 'Toutes les alertes' : 'Mes cas',
            'generated' => now()->locale('fr')->translatedFormat('j F Y à H\hi'),
            'by' => $user->name,
            'logo' => is_file($logo) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo)) : null,
            'filters' => array_filter([
                'Statut' => $request->filled('statut') ? gn_statut($request->input('statut')) : null,
                'Catégorie' => $request->filled('categorie') ? optional(\App\Models\Ticket\Category::find((int) $request->input('categorie')))->name : null,
                'Recherche' => $request->filled('q') ? $request->input('q') : null,
            ]),
        ])->render();

        $opts = new Options();
        $opts->set('isRemoteEnabled', false);
        $opts->set('defaultFont', 'DejaVu Sans');
        $opts->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($opts);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        // Numérotation des pages
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->get_font('DejaVu Sans', 'normal');
        $w = $canvas->get_width();
        $h = $canvas->get_height();
        $canvas->page_text($w - 110, $h - 28, 'Page {PAGE_NUM} / {PAGE_COUNT}', $font, 8, [0.33, 0.31, 0.42]);
        $canvas->page_text(36, $h - 28, 'GANXÔ Alerte VBG · Données sensibles, ne pas diffuser', $font, 8, [0.33, 0.31, 0.42]);

        Log::info('rapport alertes pdf', ['user_id' => $user->id, 'alertes' => $stats['total']]);

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="rapport-alertes-vbg-' . now()->format('Y-m-d') . '.pdf"',
            'Cache-Control' => 'no-store',
        ]);
    }
}
