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
    protected int $id = 0;

    private \DateTime $createdAt;

    private string $number = '';

    private string $info = '';

    private string $name1 = '';

    private string $name2 = '';

    private string $name1Eng = '';

    private string $name2Eng = '';

    private int $status = 0;

    private int $lotType = 0;

    private float $weight = 0.0;

    private int $leadTime = 0;

    private string $EUitemgroup = '';

    public function __construct()
    {
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

    public function getName1() : string
    {
        return $this->name1;
    }

    public function getName2() : string
    {
        return $this->name2;
    }

    public function getName1Eng() : string
    {
        return $this->name1Eng;
    }

    public function getName2Eng() : string
    {
        return $this->name2Eng;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    public function getLotType() : int
    {
        return $this->lotType;
    }

    public function getWeight() : float
    {
        return $this->weight;
    }

    public function getLeadTime() : int
    {
        return $this->leadTime;
    }

    public function getEUItemGroup() : string
    {
        return $this->EUitemgroup;
    }
}
