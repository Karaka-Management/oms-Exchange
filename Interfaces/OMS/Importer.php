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

namespace Modules\Exchange\Interfaces\OMS;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Message\RequestAbstract;
use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\IO\Zip\Zip;
use Modules\Media\Controller\ApiController;

/**
 * OMS import class
 *
 * @package Modules\Exchange\Models\Interfaces\OMS
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Importer extends ImporterAbstract
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    private ConnectionAbstract $remote;

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
        $this->importLanguage();
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
    public function importFromRequest(RequestAbstract $request) : bool
    {
        $start = new \DateTime($request->getData('start') ?? 'now');
        $end   = new \DateTime($request->getData('end') ?? 'now');

        if ($request->getData('db') !== null) {
            $this->remote = ConnectionFactory::create([
                'db'             => (string) ($request->getData('db') ?? ''),
                'host'           => (string) ($request->getData('host') ?? ''),
                'port'           => (int) ($request->getData('port') ?? 0),
                'database'       => (string) ($request->getData('database') ?? ''),
                'login'          => (string) ($request->getData('login') ?? ''),
                'password'       => (string) ($request->getData('password') ?? ''),
                'datetimeformat' => (string) ($request->getData('datetimeformat') ?? 'Y-m-d H:i:s'),
            ]);

            $this->remote->connect();

            if ($this->remote->getStatus() !== DatabaseStatus::OK) {
                return false;
            }
        }

        $this->account = $request->header->account;

        if (((bool) ($request->getData('language') ?? false))) {
            $this->importLanguage($request);
        }

        return true;
    }

    /**
     * Import language
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importLanguage(RequestAbstract $request) : void
    {
        $upload = ApiController::uploadFilesToDestination($request->getFiles());
    }
}
