<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Media
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Exchange\Models\NullReport;
use Modules\Media\Models\NullCollection;

/** @var \phpOMS\Views\View $this */

/** @var \Modules\Exchange\Models\Report $report */
$report = $this->data['report'] ?? new NullReport();

/** @var \Modules\Media\Models\Collection $collection */
$collection = $this->data['defaultTemplates'] ?? new NullCollection();

require_once $collection->findFile('.pdf.php')->getAbsolutePath();

/** @phpstan-import-type DefaultPdf from ../../../../Admin/Install/Media/PdfDefaultTemplate/pdfTemplate.pdf.php */
$pdf = new DefaultPdf();

$topPos = $pdf->getY();

$tbl = '<table border="1" cellpadding="0" cellspacing="0">';

// headline
$first = \reset($report->data);
if ($first === false) {
    $first = [];
}

$headlines = \array_keys($first);
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
