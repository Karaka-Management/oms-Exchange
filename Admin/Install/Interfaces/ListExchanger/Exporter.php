<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Interfaces
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Interface;

use Modules\Exchange\Models\ExchangeLog;
use Modules\Exchange\Models\ExchangeType;
use Modules\Exchange\Models\ExporterAbstract;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\File\FileUtils;
use phpOMS\Views\View;

/**
 * DB export class
 *
 * @package Modules\Exchange\Models\Interfaces\List
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Exporter extends ExporterAbstract
{
    /**
     * Export all data in time span
     *
     * @param array     $data  Export data
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function export(array $data, \DateTime $start, \DateTime $end) : array
    {
        $lang             = [];
        $lang['Exchange'] = include __DIR__ . '/Lang/' . $data['language'] . '.lang.php';

        $this->l11n->loadLanguage($data['language'], 'Exchange', $lang);

        $result = $this->exportReport($data);

        $log            = new ExchangeLog();
        $log->createdBy = $data['account'] ?? 0;
        $log->type      = ExchangeType::EXPORT;
        $log->message   = $this->l11n->getText($data['language'], 'Exchange', '', 'ReportExported');
        $log->subtype   = 'language';

        $result['logs'] = [$log];

        return $result;
    }

    /**
     * Create export.
     *
     * @return array{type:string, name:string, content:string}
     *
     * @since 1.0.0
     */
    private function exportReport(array $data) : array
    {
        $content = '';
        switch ($data['type']) {
            case 'pdf':
                $content = $this->pdfExport($data);
                break;
            case 'csv':
                $content = $this->csvExport($data);
                break;
            case 'xls':
            case 'xlsx':
                $content = $this->excelExport($data);
                break;
            case 'xml':
                $content = $this->xmlExport($data);
                break;
            case 'json':
                $content = $this->jsonExport($data);
                break;
            case 'htm':
            case 'html':
                $content = $this->htmlExport($data);
                break;
            case 'doc':
            case 'docx':
                $content = $this->wordExport($data);
                break;
            default:
        }

        return [
            'type'    => 'file',
            'name'    => 'export.' . $data['type'],
            'content' => $content,
        ];
    }

    /**
     * Create pdf export
     *
     * @param array $data Data to export
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function pdfExport(array $data) : string
    {
        $view = new View();
        $view->setTemplate(\substr(
            FileUtils::absolute(__DIR__ . '/PdfListExporter/defaultPdfListExporter'),
            \strlen(FileUtils::absolute(View::BASE_PATH))
        ), 'pdf.php');

        $view->data['defaultTemplates'] = $data['templates'];
        $view->data['defaultAssets']    = $data['assets'];
        $view->data['report']           = $data['report'];

        return $view->render();
    }

    /**
     * Create csv export
     *
     * @param array $data Data to export
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function csvExport(array $data) : string
    {
        $view = new View();
        $view->setTemplate(\substr(
            FileUtils::absolute(__DIR__ . '/CsvListExporter/defaultCsvListExporter'),
            \strlen(FileUtils::absolute(View::BASE_PATH))
        ), 'pdf.php');

        $view->data['defaultTemplates'] = $data['templates'];
        $view->data['defaultAssets']    = $data['assets'];
        $view->data['report']           = $data['report'];

        return $view->render();
    }

    /**
     * Create excel export
     *
     * @param array $data Data to export
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function excelExport(array $data) : string
    {
        $view = new View();
        $view->setTemplate(\substr(
            FileUtils::absolute(__DIR__ . '/ExcelListExporter/defaultExcelListExporter'),
            \strlen(FileUtils::absolute(View::BASE_PATH))
        ), 'pdf.php');

        $view->data['defaultTemplates'] = $data['templates'];
        $view->data['defaultAssets']    = $data['assets'];
        $view->data['report']           = $data['report'];

        return $view->render();
    }

    /**
     * Create xml export
     *
     * @param array $data Data to export
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function xmlExport(array $data) : string
    {
        $defaultTemplates = $data['templates'] ?? [];
        $report           = $data['report'] ?? null;
        $type             = $data['type'] ?? 'csv';

        return '';
    }

    /**
     * Create html export
     *
     * @param array $data Data to export
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function htmlExport(array $data) : string
    {
        $defaultTemplates = $data['templates'] ?? [];
        $report           = $data['report'] ?? null;
        $type             = $data['type'] ?? 'csv';

        return '';
    }

    /**
     * Create word export
     *
     * @param array $data Data to export
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function wordExport(array $data) : string
    {
        $defaultTemplates = $data['templates'] ?? [];
        $report           = $data['report'] ?? null;
        $type             = $data['type'] ?? 'csv';

        return '';
    }

    /**
     * Create json export
     *
     * @param array $data Data to export
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function jsonExport(array $data) : string
    {
        $defaultTemplates = $data['templates'] ?? [];
        $report           = $data['report'] ?? null;
        $type             = $data['type'] ?? 'csv';

        return '';
    }

    /**
     * Export data from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function exportFromRequest(RequestAbstract $request, ResponseAbstract $response) : array
    {
        return [];
    }
}
