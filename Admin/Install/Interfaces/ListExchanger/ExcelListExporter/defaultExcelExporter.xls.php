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
use PhpOffice\PhpSpreadsheet\IOFactory;
use phpOMS\Utils\StringUtils;

/** @var \phpOMS\Views\View $this */

/** @var \Modules\Exchange\Models\Report $report */
$report = $this->data['report'] ?? new NullReport();

/** @var \Modules\Media\Models\Collection $collection */
$collection = $this->data['defaultTemplates'] ?? new NullCollection();

include $collection->findFile('.xls.php')->getAbsolutePath();

$spreadsheet = new DefaultExcel();

$first = \reset($report->data);
if ($first === false) {
    $first = [];
}

$headlines = \array_keys($first);
foreach ($headlines as $j => $headline) {
    $spreadsheet->getActiveSheet()->setCellValue(StringUtils::intToAlphabet($j + 1) . 1, $headline);
}

foreach ($report->data as $i => $row) {
    foreach ($row as $j => $cell) {
        $spreadsheet->getActiveSheet()->setCellValue(StringUtils::intToAlphabet($j + 1) . ($i + 2), $cell);
    }
}

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
