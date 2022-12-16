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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Interfaces\Intrexx;

use Modules\Exchange\Models\ImporterAbstract;
use phpOMS\Message\RequestAbstract;

/**
 * Intrexx import class
 *
 * @package Modules\Exchange\Models\Interfaces\Intrexx
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @codeCoverageIgnore
 */
final class Importer extends ImporterAbstract
{
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
        return [];
    }
}
