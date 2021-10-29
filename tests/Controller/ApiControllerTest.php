<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
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
        $this->app->orgId          = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../../Web/Api/Hooks.php');
        $this->app->sessionManager = new HttpSession(36000);

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission = new AccountPermission();
        $permission->setUnit(1);
        $permission->setApp('backend');
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
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('interface', 'OMS');

        $this->module->apiInterfaceInstall($request, $response);
        self::assertEquals(1, $response->get('')['response']->getId());
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
            \copy(__DIR__ . '/../Interfaces/OMS/test.csv', __DIR__ . '/../Interfaces/OMS/test_tmp.csv');
        }

        TestUtils::setMember($request, 'files', [
            'file0' => [
                'name'     => 'test.csv',
                'type'     => 'csv',
                'tmp_name' => __DIR__ . '/../Interfaces/OMS/test_tmp.csv',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/../Interfaces/OMS/test_tmp.csv'),
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
