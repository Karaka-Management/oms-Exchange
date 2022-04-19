<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange\Interfaces\GSD\Model
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Interfaces\GSD\Model;

/**
 * Model class.
 *
 * @package Modules\Exchange\Interfaces\GSD\Model
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 *
 * @codeCoverageIgnore
 */
class GSDArticle
{
    public int $id = 0;

    public int $createdBy = 0;

    public \DateTimeImmutable $createdAt;

    public bool $isDiscontinued = false;

    public bool $isBlocked = false;

    public string $number = '';

    public string $infoSales = '';

    public string $infoPurchase = '';

    public string $infoWarehouse = '';

    public string $name1 = '';

    public string $name2 = '';

    public string $name1Eng = '';

    public string $name2Eng = '';

    public int $status = 0;

    public string $lotManagement = '';

    public bool $hasSN = false;

    public float $weight = 0.0;

    public float $height = 0.0;

    public float $length = 0.0;

    public float $volume = 0.0;

    public string $purchaseUnit = '';

    public bool $manualLotUse = true;

    public int $leadTimeWeeks = 0;

    public int $leadTimeDays = 0;

    public int $leadTime = 0;

    public float $minimalStock = 0.0;

    public bool $negativeStock = false;

    public string $customsId = '';

    public string $unnumber = '';

    public string $EUitemgroup = '';

    public string $inspectionDepartment = '';

    public string $medicinProductClass = '';

    public bool $exportItem = false;

    public bool $nonEUItem = false;

    public bool $dualUse = false;

    public int $inShop = 0;

    public string $sectionGroup = ''; // Sparte

    public string $salesGroup = ''; // Umsatzgruppe

    public string $segment = ''; // Segment

    public int $productGroup = 0;

    public string $earningsIndicator = '';

    public string $costsIndicator = '';

    public float $weightTinplate = 0.0; // Weissblech

    public float $weightOtherComposites = 0.0; // Sonstige Verbunde

    public float $weightOther = 0.0; // Sonstiges

    public float $weightPET = 0.0; // PET

    public float $weightPaper = 0.0;

    public float $weightNatureProducts = 0.0;

    public float $weightAcrylics = 0.0;

    public float $weightCarton = 0.0;

    public float $weightGlas = 0.0;

    public float $weightAluminium = 0.0;

    public float $weightGross = 0.0;

    public float $weightNet = 0.0;

    public array $prices = []; // @todo implement from [Preise] where ParentID = 1, 2, 4??? for sales price and ParentType = 2 for purchase price?

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }
}
