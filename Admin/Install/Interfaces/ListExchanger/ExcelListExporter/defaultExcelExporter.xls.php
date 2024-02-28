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

use PhpOffice\PhpSpreadsheet\IOFactory;
use phpOMS\Utils\StringUtils;

/** @var \phpOMS\Views\View $this */

/** @var array<array> $data */
$report = $this->data['report'] ?? [];

include $this->data['defaultTemplates']->findFile('.xls.php')->getAbsolutePath();

$spreadsheet = new DefaultExcel();

$headlines = \array_keys(\reset($report->data));
foreach ($headlines as $j => $headline) {
    $spreadsheet->getActiveSheet()->setCellValue(StringUtils::intToAlphabet($j + 1) . 1, $headline);
}

foreach ($data as $i => $row) {
    foreach ($row as $j => $cell) {
        $spreadsheet->getActiveSheet()->setCellValue(StringUtils::intToAlphabet($j + 1) . ($i + 2), $cell);
    }
}

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
