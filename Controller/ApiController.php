<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
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
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\NullCollection;
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
 * @license OMS License 2.0
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExchangeImport(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $import = $this->importDataFromRequest($request);

        if (isset($import['logs'])) {
            foreach ($import['logs'] as $log) {
                $this->createModel($request->header->account, $log, ExchangeLogMapper::class, 'import', $request->getOrigin());
            }
        }

        if ($import['status']) {
            $this->createStandardUpdateResponse($request, $response, []);
        } else {
            $this->createInvalidUpdateResponse($request, $response, []);
        }
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
                    if ($request->hasData('dbtype')) {
                        $remoteConnection = ConnectionFactory::create([
                            'db'       => (string) $request->getData('dbtype'),
                            'host'     => $request->getDataString('dbhost') ?? '',
                            'port'     => $request->getDataInt('dbport') ?? 0,
                            'database' => $request->getDataString('dbdatabase') ?? '',
                            'login'    => $request->getDataString('dblogin') ?? '',
                            'password' => $request->getDataString('dbpassword') ?? '',
                        ]);
                    }

                    $remoteConnection->connect();

                    $importer = new \Modules\Exchange\Interface\Importer(
                        $this->app->dbPool->get(),
                        $remoteConnection,
                        new L11nManager()
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
        if (($val['title'] = !$request->hasData('title'))
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInterfaceInstall(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateInterfaceInstall($request))) {
            $response->data['interface_install'] = new FormValidation($val);
            $response->header->status            = RequestStatusCode::R_400;

            return;
        }

        // is allowed to create
        if (!$this->app->accountManager->get($request->header->account)
            ->hasPermission(PermissionType::CREATE, $this->app->unitId, null, self::NAME, PermissionCategory::TEMPLATE)
        ) {
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        if (!empty($uploadedFiles = $request->files)) {
            $path = '/Modules/Exchange/Interface/' . $request->getData('title');

            /** @var \Modules\Media\Models\Media[] $uploaded */
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: $request->getDataList('names'),
                fileNames: $request->getDataList('filenames'),
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH
            );

            $collection = null;
            foreach ($uploaded as $media) {
                if ($request->hasData('type')) {
                    $this->createModelRelation(
                        $request->header->account,
                        $media->id,
                        $request->getDataInt('type'),
                        MediaMapper::class,
                        'types',
                        '',
                        $request->getOrigin()
                    );
                }

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Collection $collection */
                    $collection = MediaMapper::getParentCollection($path)
                        ->limit(1)
                        ->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path,
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $interface = $this->createInterfaceFromRequest($request, $collection?->id ?? 0);

        $this->createModel($request->header->account, $interface, InterfaceManagerMapper::class, 'interface', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $interface);
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
        $interface->title     = $request->getDataString('title') ?? '';
        $interface->hasExport = $request->getDataBool('export') ?? false;
        $interface->hasImport = $request->getDataBool('import') ?? false;
        $interface->website   = $request->getDataString('website') ?? '';
        $interface->version   = $request->getDataString('version') ?? '';
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
                        new L11nManager()
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
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $setting = $this->createSettingFromRequest($request);

        $this->createModel($request->header->account, $setting, ExchangeSettingMapper::class, 'setting', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $setting);
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
        if (($val['title'] = !$request->hasData('title'))
            || ($val['id'] = !$request->hasData('id'))
            || ($val['data'] = !$request->hasData('data'))
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
        $setting->title    = $request->getDataString('title') ?? '';
        $setting->exchange = $request->getDataInt('id') ?? 0;
        $setting->setData($request->getDataJson('data'));

        return $setting;
    }
}
