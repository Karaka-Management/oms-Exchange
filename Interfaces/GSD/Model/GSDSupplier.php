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
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
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
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    protected \DateTimeImmutable $createdAt;

    /**
     * Customer number
     *
     * @var string
     * @since 1.0.0
     */
    private string $number = '';

    /**
     * Info text
     *
     * @var string
     * @since 1.0.0
     */
    private string $info = '';

    /**
     * VAT id
     *
     * @var string
     * @since 1.0.0
     */
    private string $taxid = '';

    /**
     * BICC
     *
     * @var string
     * @since 1.0.0
     */
    private string $bic = '';

    /**
     * IBAN
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $iban = '';

    /**
     * Address
     *
     * @var GSDAddress
     * @since 1.0.0
     */
    private GSDAddress $addr;

    /**
     * Delivery status.
     *
     * Can invoices get created
     *
     * @var int
     * @since 1.0.0
     */
    private int $deliveryStatus = 0;

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

    /**
     * Get supplier number
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getNumber() : string
    {
        return $this->number;
    }

    /**
     * Get information text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getInfo() : string
    {
        return $this->info;
    }

    /**
     * Get VAT Id
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getTaxId() : string
    {
        return $this->taxid;
    }

    /**
     * Get BIC
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getBIC() : string
    {
        return $this->bic;
    }

    /**
     * Get IBAN
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getIban() : string
    {
        return $this->iban;
    }

    /**
     * Get main address
     *
     * @return GSDAddress
     *
     * @since 1.0.0
     */
    public function getAddress() : GSDAddress
    {
        return $this->addr;
    }

    /**
     * Get deivery status
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getDeliveryStatus() : int
    {
        return $this->deliveryStatus;
    }
}
