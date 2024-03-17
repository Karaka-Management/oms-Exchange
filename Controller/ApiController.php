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
use Modules\Admin\Models\SettingsEnum as AdminSettingsEnum;
use Modules\Exchange\Models\ExchangeLogMapper;
use Modules\Exchange\Models\ExchangeSetting;
use Modules\Exchange\Models\ExchangeSettingMapper;
use Modules\Exchange\Models\InterfaceManager;
use Modules\Exchange\Models\InterfaceManagerMapper;
use Modules\Exchange\Models\PermissionCategory;
use Modules\Exchange\Models\Report;
use Modules\Exchange\Models\SettingsEnum;
use Modules\Media\Models\CollectionMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\NullCollection;
use Modules\Media\Models\PathSettings;
use Modules\Organization\Models\UnitMapper;
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
        $dbData = [];
        if ($request->hasData('dbtype')) {
            $dbData = [
                'dbtype'     => (string) $request->getData('dbtype'),
                'dbhost'     => $request->getDataString('dbhost') ?? '',
                'dbport'     => $request->getDataInt('dbport') ?? 0,
                'dbdatabase' => $request->getDataString('dbdatabase') ?? '',
                'dblogin'    => $request->getDataString('dblogin') ?? '',
                'dbpassword' => $request->getDataString('dbpassword') ?? '',
            ];
        }

        $importer = $this->getImporter((int) $request->getData('id'), $dbData);
        $import   = $importer === null ? [] : $importer->importFromRequest($request, $response);

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
     * Get importer by id
     *
     * @param int   $id     Id of the importer
     * @param array $dbData Database connection data
     *
     * @return null|\Modules\Exchange\Interface\Importer
     *
     * @since 1.0.0
     */
    private function getImporter(int $id, array $dbData) : ?\Modules\Exchange\Interface\Importer
    {
        $importer = null;

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('source/sources')
            ->where('id', $id)
            ->execute();

        if ($interface->id === 0) {
            return null;
        }

        $files = $interface->source->getSources();
        foreach ($files as $tMedia) {
            $path = $tMedia->getAbsolutePath();

            switch (true) {
                case StringUtils::endsWith($path, 'Importer.php'):
                    require_once $path;

                    $remoteConnection = new NullConnection();
                    if (!empty($dbData)) {
                        $remoteConnection = ConnectionFactory::create([
                            'db'       => (string) $dbData['dbtype'],
                            'host'     => $dbData['dbhost'] ?? '',
                            'port'     => $dbData['dbport'] ?? 0,
                            'database' => $dbData['dbdatabase'] ?? '',
                            'login'    => $dbData['dblogin'] ?? '',
                            'password' => $dbData['dbpassword'] ?? '',
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

        return $importer;
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
        $exporter = $this->getExporter((int) $request->getData('id'));
        $export   = $exporter === null ? [] : $exporter->exportFromRequest($request, $response);

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

            $response->set($request->uri->__toString(), $export['content']);
        }
    }

    /**
     * Get exporter by id
     *
     * @param int $id Id of the exporter
     *
     * @return null|\Modules\Exchange\Interface\Exporter
     *
     * @since 1.0.0
     */
    private function getExporter(int $id) : ?\Modules\Exchange\Interface\Exporter
    {
        $exporter = null;

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('source/sources')
            ->where('id', $id)
            ->execute();

        if ($interface->id === 0) {
            return null;
        }

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

        return $exporter;
    }

    /**
     * Get the data of the export
     *
     * @param int   $id   Exporter id
     * @param array $data Export data
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function exportData(int $id, array $data) : array
    {
        $exporter = $this->getExporter($id);

        return $exporter === null ? [] : $exporter->export($data, new \DateTime(), new \DateTime());
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

    /**
     * Api method to export a report
     *
     * @param RequestAbstract $request  Request
     * @param HttpResponse    $response Response
     * @param Report          $report   Report to export
     * @param string          $type     Export type (e.g. pdf,csv,html,xml,json,xls)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function apiExportReport(
        RequestAbstract $request,
        ResponseAbstract $response,
        Report $report,
        string $type
    ) : void
    {
        /** @var \Model\Setting[] $settings */
        $settings = $this->app->appSettings->get(null,
            [
                AdminSettingsEnum::DEFAULT_TEMPLATES,
                AdminSettingsEnum::DEFAULT_ASSETS,
                SettingsEnum::DEFAULT_LIST_EXPORT,
            ],
            unit: $this->app->unitId,
            module: self::NAME
        );

        $exporter = $this->getExporter((int) $settings[SettingsEnum::DEFAULT_LIST_EXPORT]->content);
        if ($exporter === null) {
            $response->header->status = RequestStatusCode::R_404;
            $this->createInvalidReturnResponse($request, $response, []);

            return;
        }

        $defaultTemplates = new NullCollection();
        $defaultAssets    = new NullCollection();

        /** @var \Modules\Media\Models\Collection[] $collections */
        $collections = CollectionMapper::get()
            ->with('sources')
            ->where('id', [
                (int) $settings[AdminSettingsEnum::DEFAULT_TEMPLATES]->content,
                (int) $settings[AdminSettingsEnum::DEFAULT_ASSETS]->content,
            ], 'IN')
            ->execute();

        foreach ($collections as $collection) {
            if ($collection->id === (int) $settings[AdminSettingsEnum::DEFAULT_TEMPLATES]->content) {
                $defaultTemplates = $collection;
            } elseif ($collection->id === (int) $settings[AdminSettingsEnum::DEFAULT_ASSETS]->content) {
                $defaultAssets = $collection;
            }
        }

        $organization = UnitMapper::get()
            ->with('contacts')
            ->with('mainAddress')
            ->with('attributes')
            ->where('id', $this->app->unitId)
            ->execute();

        $export = $exporter === null ? [] : $exporter->export(
            [
                'assets'       => $defaultAssets,
                'templates'    => $defaultTemplates,
                'report'       => $report,
                'type'         => $type,
                'organization' => $organization,
                'language'     => $response->header->l11n->language,
            ],
            new \DateTime(), new \DateTime()
        );

        foreach ($export['logs'] as $log) {
            $log->exchange = (int) $settings[SettingsEnum::DEFAULT_LIST_EXPORT]->content;
            $this->createModel($request->header->account, $log, ExchangeLogMapper::class, 'export', $request->getOrigin());
        }

        $file = \explode('.', $export['name']);

        $response->header->setDownloadable($file[0], $file[1]);

        $response->set($request->uri->__toString(), $export['content']);
    }
}
