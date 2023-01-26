<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\Exchange\Models\ExchangeLogMapper;
use Modules\Exchange\Models\ExchangeSetting;
use Modules\Exchange\Models\ExchangeSettingMapper;
use Modules\Exchange\Models\InterfaceManager;
use Modules\Exchange\Models\InterfaceManagerMapper;
use Modules\Exchange\Models\PermissionCategory;
use Modules\Media\Models\CollectionMapper;
use Modules\Media\Models\NullCollection;
use Modules\Media\Models\NullMedia;
use Modules\Media\Models\PathSettings;
use phpOMS\Account\PermissionType;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\Utils\StringUtils;

/**
 * Exchange controller class.
 *
 * @package Modules\Exchange
 * @license OMS License 1.0
 * @link    https://jingga.app
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
    public function apiExchangeImport(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        $import  = $this->importDataFromRequest($request);
        $status  = NotificationLevel::ERROR;
        $message = 'Import failed.';

        if (isset($import['logs'])) {
            foreach ($import['logs'] as $log) {
                $this->createModel($request->header->account, $log, ExchangeLogMapper::class, 'import', $request->getOrigin());
            }
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
        $importer = null;

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('source/sources')
            ->where('id', $request->getData('id'))
            ->execute();

        $files = $interface->source->getSources();
        foreach ($files as $tMedia) {
            $path = $tMedia->getAbsolutePath();

            switch (true) {
                case StringUtils::endsWith($path, 'Importer.php'):
                    require_once $path;

                    $remoteConnection = new NullConnection();
                    if (!empty($request->getData('dbtype'))) {
                        $remoteConnection = ConnectionFactory::create([
                            'db'       => (string) $request->getData('dbtype'),
                            'host'     => $request->getData('dbhost', 'string'),
                            'port'     => $request->getData('dbport', 'int'),
                            'database' => $request->getData('dbdatabase', 'string'),
                            'login'    => $request->getData('dblogin', 'string'),
                            'password' => $request->getData('dbpassword', 'string'),
                        ]);
                    }

                    $importer = new \Modules\Exchange\Interface\Importer(
                        $this->app->dbPool->get(),
                        $remoteConnection,
                        new L11nManager($this->app->appName)
                    );

                    break;
            }
        }

        /** @var \Modules\Exchange\Models\ImporterAbstract $importer */
        return $importer === null ? [] : $importer->importFromRequest($request);
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
        if (($val['title'] = empty($request->getData('title')))
            || ($val['files'] = empty($request->getFiles()))
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
    public function apiInterfaceInstall(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        $uploadedFiles = $request->getFiles();
        $files         = [];

        if (!empty($val = $this->validateInterfaceInstall($request))) {
            $response->set('interface_install', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        // is allowed to create
        if (!$this->app->accountManager->get($request->header->account)->hasPermission(PermissionType::CREATE, $this->app->unitId, null, self::NAME, PermissionCategory::TEMPLATE)) {
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $path = '/Modules/Exchange/Interface/' . $request->getData('title');

        /** @var \Modules\Media\Models\Media[] $uploaded */
        $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
            $request->getDataList('names'),
            $request->getDataList('filenames'),
            $uploadedFiles,
            $request->header->account,
            __DIR__ . '/../../../Modules/Media/Files' . $path,
            $path,
            pathSettings: PathSettings::FILE_PATH
        );

        foreach ($uploaded as $upload) {
            if ($upload instanceof NullMedia) {
                continue;
            }

            $files[] = $upload;
        }

        /** @var \Modules\Media\Models\Collection $collection */
        $collection = $this->app->moduleManager->get('Media')->createMediaCollectionFromMedia(
            (string) ($request->getData('name') ?? ''),
            (string) ($request->getData('description') ?? ''),
            $files,
            $request->header->account
        );

        if ($collection instanceof NullCollection) {
            $response->header->status = RequestStatusCode::R_403;
            $this->fillJsonResponse($request, $response, NotificationLevel::ERROR, 'Interface', 'Couldn\'t create collection for interface', null);

            return;
        }

        $collection->setPath('/Modules/Media/Files/Modules/Exchange/Interface/' . ((string) ($request->getData('title') ?? '')));
        $collection->setVirtualPath('/Modules/Exchange/Interface');

        CollectionMapper::create()->execute($collection);

        $interface = $this->createInterfaceFromRequest($request, $collection->getId());

        $this->createModel($request->header->account, $interface, InterfaceManagerMapper::class, 'interface', $request->getOrigin());

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Interface', 'Interface successfully created', $interface);
    }

    /**
     * Method to create template from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return InterfaceManager
     *
     * @since 1.0.0
     */
    private function createInterfaceFromRequest(RequestAbstract $request, int $collectionId) : InterfaceManager
    {
        $interface            = new InterfaceManager();
        $interface->title     = (string) ($request->getData('title') ?? '');
        $interface->hasExport = (bool) ($request->getData('export') ?? false);
        $interface->hasImport = (bool) ($request->getData('import') ?? false);
        $interface->website   = (string) ($request->getData('website') ?? '');
        $interface->version   = (string) ($request->getData('version') ?? '');
        $interface->createdBy = new NullAccount($request->header->account);

        if ($collectionId > 0) {
            $interface->source = new NullCollection($collectionId);
        }

        $interface->createdBy = new NullAccount($request->header->account);

        return $interface;
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
    public function apiExchangeExport(RequestAbstract $request, HttpResponse $response, mixed $data = null) : void
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
        $exporter = null;

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('source/sources')
            ->where('id', $request->getData('id'))
            ->execute();

        $files = $interface->source->getSources();
        foreach ($files as $tMedia) {
            $path = $tMedia->getAbsolutePath();

            switch (true) {
                case StringUtils::endsWith($path, 'Exporter.php'):
                    require_once $path;

                    $exporter = new \Modules\Exchange\Interface\Exporter(
                        $this->app->dbPool->get(),
                        new NullConnection(),
                        new L11nManager($this->app->appName)
                    );

                    break;
            }
        }

        return $exporter === null ? [] : $exporter->exportFromRequest($request);
    }

    /**
     * Api method to create setting
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
    public function apiExchangeSettingCreate(RequestAbstract $request, HttpResponse $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateSettingCreate($request))) {
            $response->set('setting_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $setting = $this->createSettingFromRequest($request);

        $this->createModel($request->header->account, $setting, ExchangeSettingMapper::class, 'setting', $request->getOrigin());

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Setting', 'Setting successfully created', $setting);
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
    private function validateSettingCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))
            || ($val['id'] = empty($request->getData('id')))
            || ($val['data'] = empty($request->getData('data')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Method to create template from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ExchangeSetting
     *
     * @since 1.0.0
     */
    private function createSettingFromRequest(RequestAbstract $request) : ExchangeSetting
    {
        $setting           = new ExchangeSetting();
        $setting->title    = (string) ($request->getData('title') ?? '');
        $setting->exchange = (int) ($request->getData('id') ?? 0);
        $setting->setData(\json_decode((string) ($request->getData('data') ?? '{}'), true));

        return $setting;
    }
}
