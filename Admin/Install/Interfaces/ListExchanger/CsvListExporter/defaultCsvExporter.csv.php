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
/** @var array $data */
$report = $this->data['report'] ?? [];
$headlines = \array_keys(\reset($report->data));

$out = \fopen('php://output', 'w');
if ($out !== false) {
    \fputcsv($out, $headlines);

    foreach ($report->data as $row) {
        \fputcsv($out, $row);
    }

    \fclose($out);
}

echo $out;
