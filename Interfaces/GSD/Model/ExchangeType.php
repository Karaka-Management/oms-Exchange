<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */

declare(strict_types=1);

namespace Modules\Exchange\Interfaces\GSD\Model;

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
    public const CUSTOMER = 1;

    public const SUPPLIER = 2;

    public const ARTICLE = 3;

    public const BOOKING = 4;

    public const ACCOUNT = 5;

    public const ADDRESS = 6;

    public const COSTCENTER = 7;

    public const COSTOBJECT = 8;

    public const INVOICE = 9;
}
