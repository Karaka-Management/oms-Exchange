<?php
/**
 * Karaka
 *
 * PHP Version 8.0
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
class GSDAddress
{
    public int $id = 0;

    public string $name1 = '';

    public string $name2 = '';

    public string $name3 = '';

    public string $city = '';

    public string $country = '';

    public string $zip = '';

    public string $street = '';

    public string $phone = '';

    public string $fax = '';

    public string $email = '';

    public string $website = '';

    public int $createdBy = 0;

    public \DateTimeImmutable $createdAt;

    /**
     * Construct.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }
}
