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
final class GSDSupplier
{
    private int $id = 0;

    /**
     * Creator.
     *
     * @var int
     * @since 1.0.0
     */
    protected $createdBy = 0;

    /**
     * Created.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected \DateTime $createdAt;

    private string $number = '';

    private string $info = '';

    private string $taxid = '';

    private string $bic = '';

    private string $iban = '';

    private GSDAddress $addr;

    private int $deliveryStatus = 0;

    public function __construct()
    {
        $this->addr = new GSDAddress();
        $this->createdAt = new \DateTime('now');
    }

    public function getNumber() : string
    {
        return $this->number;
    }

    public function getInfo() : string
    {
        return $this->info;
    }

    public function getTaxId() : string
    {
        return $this->taxid;
    }

    public function getBIC() : string
    {
        return $this->bic;
    }

    public function getIban() : string
    {
        return $this->iban;
    }

    public function getAddress() : GSDAddress
    {
        return $this->addr;
    }

    public function getDeliveryStatus() : int
    {
        return $this->deliveryStatus;
    }
}
