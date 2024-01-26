<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Exchange\Controller\ApiController;
use Modules\Exchange\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/admin/exchange/import/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\ApiController:apiExchangeImport',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::IMPORT,
            ],
        ],
    ],
    '^.*/admin/exchange/export/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Exchange\Controller\ApiController:apiExchangeExport',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPORT,
            ],
        ],
    ],
];
