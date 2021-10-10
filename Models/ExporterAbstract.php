<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Interfaces
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\RequestAbstract;

/**
 * Export abstract
 *
 * @package Interfaces
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class ExporterAbstract
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected ConnectionAbstract $local;

    /**
     * L11n manager.
     *
     * @var L11nManager
     * @since 1.0.0
     */
    protected L11nManager $l11n;

    /**
     * Constructor
     *
     * @param ConnectionAbstract $local Database connection
     * @param L11nManager        $l11n  Localization manager
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $local, L11nManager $l11n)
    {
        $this->local = $local;
        $this->l11n  = $l11n;
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
    abstract public function exportFromRequest(RequestAbstract $request) : array;
}
