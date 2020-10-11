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
class GSDAddress
{
    /**
     * ID.
     *
     * @var int
     * @sicne 1.0.0
     */
    protected int $id = 0;

    /**
     * Name1.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $name1 = '';

    /**
     * Name2.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $name2 = '';

    /**
     * Name3.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $name3 = '';

    /**
     * City.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $city = '';

    /**
     * Country.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $country = '';

    /**
     * Postal code.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $zip = '';

    /**
     * street.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $street = '';

    /**
     * Phone.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $phone = '';

    /**
     * FAX.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $fax = '';

    /**
     * Email.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $email = '';

    /**
     * Website.
     *
     * @var string
     * @sicne 1.0.0
     */
    private string $website = '';

    /**
     * Creator.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $createdBy = 0;

    /**
     * Created.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    protected \DateTimeImmutable $createdAt;

    /**
     * Construct.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
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
     * Get name1
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
     * Get name3
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName3() : string
    {
        return $this->name3;
    }

    /**
     * Get city
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCity() : string
    {
        return $this->city;
    }

    /**
     * Get postal/sip
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getZip() : string
    {
        return $this->zip;
    }

    /**
     * Get street
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getStreet() : string
    {
        return $this->street;
    }

    /**
     * Get country
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     * Get phone
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPhone() : string
    {
        return $this->phone;
    }

    /**
     * Get email
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Get fax
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getFax() : string
    {
        return $this->fax;
    }

    /**
     * Get website
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getWebsite() : string
    {
        return $this->website;
    }
}
