<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Media
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Exchange\Models\NullReport;

/** @var \phpOMS\Views\View $this */

/** @var \Modules\Exchange\Models\Report $report */
$report = $this->data['report'] ?? new NullReport();

$first = \reset($report->data);
if ($first === false) {
    $first = [];
}

$headlines = \array_keys($first);

$out = \fopen('php://output', 'w');
if ($out !== false) {
    \fputcsv($out, $headlines);

    foreach ($report->data as $row) {
        \fputcsv($out, $row);
    }

    \fclose($out);
}

echo $out;
