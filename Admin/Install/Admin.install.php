<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Exchange\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Exchange\Controller\ApiController;
use Modules\Exchange\Models\SettingsEnum;

return [
    [
        'type'    => 'setting',
        'name'    => SettingsEnum::DEFAULT_LIST_EXPORT,
        'content' => '2',
        'module'  => ApiController::NAME,
    ],
];
