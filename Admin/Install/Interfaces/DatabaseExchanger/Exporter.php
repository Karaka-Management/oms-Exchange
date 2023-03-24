<?php
/**
 * Karaka
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

use Modules\Exchange\Models\ExporterAbstract;
use phpOMS\Message\RequestAbstract;

/**
 * DB export class
 *
 * @package Modules\Exchange\Models\Interfaces\OMS
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Exporter extends ExporterAbstract
{
    use ExchangeTrait;

    /**
     * Export all data in time span
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function export(\DateTime $start, \DateTime $end) : array
    {
        return [];
    }

    /**
     * Export data from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function exportFromRequest(RequestAbstract $request) : array
    {
        return $this->exchangeFromRequest($request);
    }
}
