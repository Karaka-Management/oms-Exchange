<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
use phpOMS\Message\RequestAbstract;

/**
 * Import abstract
 *
 * @package Interfaces
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class ImporterAbstract
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected ConnectionAbstract $local;

    /**
     * Constructor
     *
     * @param ConnectionAbstract $local Database connection
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $local)
    {
        $this->local = $local;
    }

    /**
     * Import data from request
     *
     * @param RequestAbstract $request Request
     *
     * @return bool
     *
     * @since 1.0.0
     */
    abstract public function importFromRequest(RequestAbstract $request) : bool;
}
