<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */

declare(strict_types=1);

namespace Modules\Exchange\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Exchange status enum.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class ExchangeType extends Enum
{
    public const IMPORT = 1;

    public const EXPORT = 2;
}
