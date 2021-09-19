<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use Modules\Exchange\Controller\ApiController;
use Modules\Exchange\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/admin/exchange/import/profile.*$' => [
        [
            'dest'       => '\Modules\Exchange\Controller\ApiController:apiExchangeImport',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::IMPORT,
            ],
        ],
    ],
    '^.*/admin/exchange/export/profile.*$' => [
        [
            'dest'       => '\Modules\Exchange\Controller\ApiController:apiExchangeExport',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::EXPORT,
            ],
        ],
    ],
];
