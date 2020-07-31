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
final class GSDArticle
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Create at.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    private \DateTime $createdAt;

    /**
     * Article number.
     *
     * @var string
     * @since 1.0.0
     */
    private string $number = '';

    /**
     * Article information
     *
     * @var string
     * @since 1.0.0
     */
    private string $info = '';

    /**
     * Name1.
     *
     * @var string
     * @since 1.0.0
     */
    private string $name1 = '';

    /**
     * Name2
     *
     * @var string
     * @since 1.0.0
     */
    private string $name2 = '';

    /**
     * English name1.
     *
     * @var string
     * @since 1.0.0
     */
    private string $name1Eng = '';

    /**
     * English name2
     *
     * @var string
     * @since 1.0.0
     */
    private string $name2Eng = '';

    /**
     * Activity status
     *
     * @var int
     * @since 1.0.0
     */
    private int $status = 0;

    /**
     * Lot type (can also be none)
     *
     * @var int
     * @since 1.0.0
     */
    private int $lotType = 0;

    /**
     * Weight
     *
     * @var float
     * @since 1.0.0
     */
    private float $weight = 0.0;

    /**
     * Lead time in days
     *
     * @var int
     * @since 1.0.0
     */
    private int $leadTime = 0;

    /**
     * EU item group
     *
     * @var string
     * @since 1.0.0
     */
    private string $EUitemgroup = '';

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Get article number
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
     * Get article information text
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
     * Get name1
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName1() : string
    {
        return $this->name1;
    }

    /**
     * Get name2
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName2() : string
    {
        return $this->name2;
    }

    /**
     * Get englisch name1
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName1Eng() : string
    {
        return $this->name1Eng;
    }

    /**
     * Get englisch name2
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName2Eng() : string
    {
        return $this->name2Eng;
    }

    /**
     * Get status
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Get lot type
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getLotType() : int
    {
        return $this->lotType;
    }

    /**
     * Get weight
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getWeight() : float
    {
        return $this->weight;
    }

    /**
     * Get lead time (in days)
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getLeadTime() : int
    {
        return $this->leadTime;
    }

    /**
     * Get EU item group
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getEUItemGroup() : string
    {
        return $this->EUitemgroup;
    }
}
