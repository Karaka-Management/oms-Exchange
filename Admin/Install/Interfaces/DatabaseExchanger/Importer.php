<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Interfaces
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Interface;

use Modules\Exchange\Models\ImporterAbstract;
use phpOMS\Message\RequestAbstract;

/**
 * DB import class
 *
 * @package Modules\Exchange\Models\Interfaces\OMS
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Importer extends ImporterAbstract
{
    use ExchangeTrait;

    /**
     * Account
     *
     * @var int
     * @since 1.0.0
     */
    private int $account = 1;

    /**
     * Import all data in time span
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function import(\DateTime $start, \DateTime $end) : void
    {
    }

    /**
     * Import data from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function importFromRequest(RequestAbstract $request) : array
    {
        return $this->exchangeFromRequest($request);
    }
}
