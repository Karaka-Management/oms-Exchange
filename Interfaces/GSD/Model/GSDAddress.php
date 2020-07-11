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
final class GSDAddress
{
    protected int $id = 0;

    private string $name1 = '';

    private string $name2 = '';

    private string $name3 = '';

    private string $city = '';

    private string $country = '';

    private string $zip = '';

    private string $street = '';

    private string $phone = '';

    private string $fax = '';

    private string $email = '';

    private string $website = '';

    public function getName1() : string
    {
        return $this->name1;
    }

    public function getName2() : string
    {
        return $this->name2;
    }

    public function getName3() : string
    {
        return $this->name3;
    }

    public function getCity() : string
    {
        return $this->city;
    }

    public function getZip() : string
    {
        return $this->zip;
    }

    public function getStreet() : string
    {
        return $this->street;
    }

    public function getCountry() : string
    {
        return $this->country;
    }

    public function getPhone() : string
    {
        return $this->phone;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getFax() : string
    {
        return $this->fax;
    }

    public function getWebsite() : string
    {
        return $this->website;
    }
}
