<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Permission category enum.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PermissionCategory extends Enum
{
    public const IMPORT = 1;

    public const EXPORT = 2;

    public const DASHBOARD = 3;

    public const TEMPLATE = 4;
}
