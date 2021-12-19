<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Exchange\Controller;

use Modules\Exchange\Models\ExchangeLogMapper;
use Modules\Exchange\Models\InterfaceManager;
use Modules\Exchange\Models\InterfaceManagerMapper;
use Modules\Media\Models\UploadFile;
use phpOMS\Autoloader;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\System\File\Local\Directory;

/**
 * Exchange controller class.
 *
 * @package Modules\Exchange
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method to import data
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExchangeImport(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        $import  = $this->importDataFromRequest($request);
        $status  = NotificationLevel::ERROR;
        $message = 'Import failed.';

        foreach ($import['logs'] as $log) {
            $this->createModel($request->header->account, $log, ExchangeLogMapper::class, 'import', $request->getOrigin());
        }

        if ($import['status']) {
            $status  = NotificationLevel::OK;
            $message = 'Import succeeded.';
        }

        $response->set($request->uri->__toString(), [
            'status'  => $status,
            'title'   => 'Exchange',
            'message' => $message,
        ]);
    }

    /**
     * Method to import data based on a request
     *
     * @param RequestAbstract $request Request
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function importDataFromRequest(RequestAbstract $request) : array
    {
        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()->where('id', $request->getData('id'))->execute();
        $dirname   = \basename(\dirname($interface->getPath()));

        if (!Autoloader::exists($class = '\\Modules\\Exchange\\Interfaces\\' . $dirname . '\\Importer')) {
            return [];
        }

        // @todo: implement real remote connection if available
        $importer = new $class($this->app->dbPool->get(), new NullConnection(), new L11nManager($this->app->appName));

        return $importer->importFromRequest($request);
    }

    /**
     * Method to validate account creation from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateInterfaceInstall(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['interface'] = empty($request->getData('interface')))
            || ($val['path'] = !\is_dir(__DIR__ . '/../Interfaces/' . $request->getData('interface')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to install exchange interface
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInterfaceInstall(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateInterfaceInstall($request))) {
            $response->set('interface_install', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $interfacePath = \realpath(__DIR__ . '/../Interfaces/' . $request->getData('interface') . '/interface.json');
        if ($interfacePath === false) {
            return; // @codeCoverageIgnore
        }

        $interface = new InterfaceManager($interfacePath);
        $interface->load();

        InterfaceManagerMapper::create()->execute($interface);

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Interface', 'Interface successfully installed', $interface);
    }

    /**
     * Api method to export data
     *
     * @param RequestAbstract $request  Request
     * @param HttpResponse    $response Response
     * @param mixed           $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExchangeExport(RequestAbstract $request, HttpResponse $response, $data = null) : void
    {
        $export = $this->exportDataFromRequest($request);

        if (!isset($export['type'], $export['logs'])) {
            $response->header->status = RequestStatusCode::R_400;

            $status  = NotificationLevel::ERROR;
            $message = 'Export failed.';

            $response->set($request->uri->__toString(), [
                'status'  => $status,
                'title'   => 'Exchange',
                'message' => $message,
            ]);

            return;
        }

        if ($export['type'] === 'file') {
            foreach ($export['logs'] as $log) {
                $this->createModel($request->header->account, $log, ExchangeLogMapper::class, 'export', $request->getOrigin());
            }

            $file = \explode('.', $export['name']);

            $response->header->setDownloadable($file[0], $file[1]);
            switch ($file[1]) {
                case 'csv':
                    $response->header->set(
                        'Content-disposition', 'attachment; filename="'
                        . $export['name']
                        . '"'
                    , true);
                    //$response->header->set('Content-Type', MimeType::M_CONF, true);
                    break;
            }

            $response->set($request->uri->__toString(), $export['content']);
        }
    }

    /**
     * Method to export data based on a request
     *
     * @param RequestAbstract $request Request
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function exportDataFromRequest(RequestAbstract $request) : array
    {
        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()->where('id', $request->getData('id'))->execute();
        $dirname   = \basename(\dirname($interface->getPath()));

        if (!Autoloader::exists($class = '\\Modules\\Exchange\\Interfaces\\' . $dirname . '\\Exporter')) {
            return [];
        }

        $exporter = new $class($this->app->dbPool->get(), new L11nManager($this->app->appName));

        return $exporter->exportFromRequest($request);
    }

    /**
     * Api method to handle file upload
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExchangeUpload(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        Directory::delete(__DIR__ . '/../tmp/');

        $upload            = new UploadFile();
        $upload->outputDir = __DIR__ . '/../tmp/';

        $upload->upload($request->getFiles());
    }
}
