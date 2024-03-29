<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Exchange\Controller\BackendController;
use Modules\Exchange\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/admin/exchange/import/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\BackendController:viewExchangeImportList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::IMPORT,
            ],
        ],
    ],
    '^/admin/exchange/export/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\BackendController:viewExchangeExportList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPORT,
            ],
        ],
    ],
    '^/admin/exchange/import/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\BackendController:viewExchangeImport',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::IMPORT,
            ],
        ],
    ],
    '^/admin/exchange/export/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\BackendController:viewExchangeExport',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPORT,
            ],
        ],
    ],
    '^/admin/exchange/log/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\BackendController:viewExchangeLogList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::DASHBOARD,
            ],
        ],
    ],
    '^/admin/exchange/log(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\BackendController:viewExchangeLog',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::DASHBOARD,
            ],
        ],
    ],
];
