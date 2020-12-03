<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
class GSDCustomer
{
    public int $id = 0;

    public int $createdBy = 0;

    public \DateTimeImmutable $createdAt;

    public bool $isBlocked = false;

    public bool $isDiscontinued = false;

    public bool $isLocked = false;

    public string $number = '';

    public string $customerType = '';

    public GSDAddress $addr;

    public string $info = '';

    public int $account = 0;

    public int $materialAccount = 0;

    public int $accountsReceivableAccount = 0;

    public string $earningsIndicator = '';

    public float $creditlimit = 0.0;

    public string $egustid = '';

    public string $taxid = '';

    public string $bic = '';

    public string $iban = '';

    public string $bankRef = '';

    public string $salesRep = '';

    public bool $isMonthlyInvoice = false;

    public bool $reminderBlock = false;

    public string $legalType = '';

    public array $partner = [];

    public array $paymentTerms = []; // @todo: implement model/mapper

    public array $addresses = []; // @todo: implement

    public array $prices = []; // @todo: implement

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
