<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Session\HttpSession;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\TestUtils;

/**
 * @testdox Modules\Exchange\tests\Controller\ApiControllerTest: Exchange api controller
 *
 * @internal
 */
final class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * @var \Modules\Exchange\Controller\ApiController
     */
    protected ModuleAbstract $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->unitId          = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../../Web/Api/Hooks.php');
        $this->app->sessionManager = new HttpSession(36000);
        $this->app->l11nManager    = new L11nManager();

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission = new AccountPermission();
        $permission->setUnit(1);
        $permission->setApp(2);
        $permission->setPermission(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION
        );

        $account->addPermission($permission);

        $this->app->accountManager->add($account);
        $this->app->router = new WebRouter();

        $this->module = $this->app->moduleManager->get('Exchange');

        TestUtils::setMember($this->module, 'app', $this->app);
    }

    /**
     * @covers Modules\Exchange\Controller\ApiController
     * @group module
     */
    public function testInterfaceInstall() : void
    {
        $exchanges = \scandir(__DIR__ . '/../Interfaces');

        if (!\is_dir(__DIR__ . '/temp')) {
            \mkdir(__DIR__ . '/temp');
        }

        foreach ($exchanges as $exchange) {
            if (!\is_dir(__DIR__ . '/../Interfaces/' . $exchange) || $exchange === '..' || $exchange === '.') {
                continue;
            }

            $data = \json_decode(\file_get_contents(__DIR__ . '/../Interfaces/' . $exchange . '/interface.json'), true);

            $response = new HttpResponse();
            $request  = new HttpRequest(new HttpUri(''));

            $request->header->account = 1;
            $request->setData('title', $data['name']);
            $request->setData('export', (bool) $data['export']);
            $request->setData('import', (bool) $data['import']);
            $request->setData('website', $data['website']);

            $files = [];

            $exchangeFiles = \scandir(__DIR__ . '/../Interfaces/' . $exchange);
            foreach ($exchangeFiles as $filePath) {
                if ($filePath === '..' || $filePath === '.') {
                    continue;
                }

                if (\is_dir(__DIR__ . '/../Interfaces/' . $exchange . '/' . $filePath)) {
                    $subdir = \scandir(__DIR__ . '/../Interfaces/' . $exchange . '/' . $filePath);
                    foreach ($subdir as $subPath) {
                        if (!\is_file(__DIR__ . '/../Interfaces/' . $exchange . '/' . $filePath . '/' . $subPath)) {
                            continue;
                        }

                        \copy(
                            __DIR__ . '/../Interfaces/' . $exchange . '/' . $filePath . '/' . $subPath,
                            __DIR__ . '/temp/' . $subPath
                        );

                        $files[] = [
                            'error'    => \UPLOAD_ERR_OK,
                            'type'     => \substr($subPath, \strrpos($subPath, '.') + 1),
                            'name'     => $filePath . '/' . $subPath,
                            'tmp_name' => __DIR__ . '/temp/' . $subPath,
                            'size'     => \filesize(__DIR__ . '/temp/' . $subPath),
                        ];
                    }
                } else {
                    if (!\is_file(__DIR__ . '/../Interfaces/' . $exchange . '/' . $filePath)) {
                        continue;
                    }

                    \copy(__DIR__ . '/../Interfaces/' . $exchange . '/' . $filePath, __DIR__ . '/temp/' . $filePath);

                    $files[] = [
                        'error'    => \UPLOAD_ERR_OK,
                        'type'     => \substr($filePath, \strrpos($filePath, '.') + 1),
                        'name'     => $filePath,
                        'tmp_name' => __DIR__ . '/temp/' . $filePath,
                        'size'     => \filesize(__DIR__ . '/temp/' . $filePath),
                    ];
                }
            }

            TestUtils::setMember($request, 'files', $files);

            $this->module->apiInterfaceInstall($request, $response);
            self::assertGreaterThan(0, $response->get('')['response']->id);
        }

        if (\is_dir(__DIR__ . '/temp')) {
            \rmdir(__DIR__ . '/temp');
        }
    }

    /**
     * @covers Modules\Exchange\Controller\ApiController
     * @group module
     */
    public function testInterfaceInstallInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiInterfaceInstall($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\Exchange\Controller\ApiController
     * @group module
     */
    public function testExport() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('id', '1');
        $request->setData('type', 'language');

        $this->module->apiExchangeExport($request, $response);
        self::assertTrue(\strlen($response->get('')) > 500);
    }

    /**
     * @covers Modules\Exchange\Controller\ApiController
     * @group module
     */
    public function testExportInvalidInterface() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('id', '9999');
        $request->setData('type', 'language');

        $this->module->apiExchangeExport($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\Exchange\Controller\ApiController
     * @group module
     */
    public function testLanguageImport() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('id', '1');
        $request->setData('type', 'language');

        if (!\is_file(__DIR__ . '/test_tmp.csv')) {
            \copy(__DIR__ . '/../Interfaces/OMS/test.csv', __DIR__ . '/test_tmp.csv');
        }

        TestUtils::setMember($request, 'files', [
            'file0' => [
                'name'     => 'test_tmp.csv',
                'type'     => 'csv',
                'tmp_name' => __DIR__ . '/test_tmp.csv',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/test_tmp.csv'),
            ],
        ]);

        $this->module->apiExchangeImport($request, $response);
        self::assertEquals(
            \date('Y-m-d'),
            \date('Y-m-d', \filemtime(__DIR__ . '/../../../TestModule/Theme/Backend/Lang/en.lang.php'))
        );
    }

    /**
     * @covers Modules\Exchange\Controller\ApiController
     * @group module
     */
    public function testImportInvalidInterface() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('id', '9999');

        $this->module->apiExchangeExport($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
