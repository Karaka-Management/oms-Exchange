<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Interfaces
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Interface;

use Modules\Exchange\Models\ImporterAbstract;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;

/**
 * DB import class
 *
 * @package Modules\Exchange\Models\Interfaces\OMS
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Importer extends ImporterAbstract
{
    use ExchangeTrait;

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
    public function import(array $data, \DateTime $start, \DateTime $end) : void
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
    public function importFromRequest(RequestAbstract $request, ResponseAbstract $response) : array
    {
        return $this->exchangeFromRequest($request);
    }
}
