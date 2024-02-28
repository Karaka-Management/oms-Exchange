<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Media
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

/** @var \phpOMS\Views\View $this */

/** @var array<array> $data */
$report = $this->data['report'] ?? [];

require_once $this->data['defaultTemplates']->findFile('.pdf.php')->getAbsolutePath();

/** @phpstan-import-type DefaultPdf from ../../../../Admin/Install/Media/PdfDefaultTemplate/pdfTemplate.pdf.php */
$pdf = new DefaultPdf();

$topPos = $pdf->getY();

$tbl = '<table border="1" cellpadding="0" cellspacing="0">';

// headline
$headlines = \array_keys(\reset($report->data));
$tbl .= '<thead><tr>';
foreach ($headlines as $headline) {
    $tbl .= '<th>' . $headline . '</th>';
}
$tbl .= '</tr></thead>';
$tbl .= '<tbody>';

if (!empty($report->data)) {
    foreach ($report->data as $row) {
        $tbl .= '<tr>';
        foreach ($row as $cell) {
            $tbl .= '<td>' . $cell . '</td>';
        }
        $tbl .= '</tr>';
    }
}

$tbl .= '</table>';

$html = $tbl;

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('export.pdf', 'I');
