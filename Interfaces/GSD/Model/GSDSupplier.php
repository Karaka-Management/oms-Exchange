<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Exchange\Interfaces\GSD\Model
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Exchange\Interfaces\GSD\Model;

/**
 * Model class.
 *
 * @package Modules\Exchange\Interfaces\GSD\Model
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class GSDSupplier
{
    public int $id = 0;

    public int $createdBy = 0;

    public \DateTimeImmutable $createdAt;

    public string $number = '';

    public string $info = '';

    public string $taxid = '';

    public string $bic = '';

    public string $iban = '';

    public GSDAddress $addr;

    public int $deliveryStatus = 0;

    /**
     * Construct.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->addr      = new GSDAddress();
        $this->createdAt = new \DateTimeImmutable('now');
    }
}
