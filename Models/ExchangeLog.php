<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use Modules\Admin\Models\Account;

/**
 * Exchange class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ExchangeLog implements \JsonSerializable
{
    /**
     * Article ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Message.
     *
     * @var string
     * @since 1.0.0
     */
    public string $message = '';

    /**
     * Fields.
     *
     * What where the values used in the exchange form to reproduce this output (exchange specific)?
     *
     * @var array
     * @since 1.0.0
     */
    private array $fields = [];

    /**
     * Log type.
     *
     * @var int
     * @since 1.0.0
     */
    public int $type = ExchangeType::IMPORT;

    /**
     * Exchange specific subtype.
     *
     * @var string
     * @since 1.0.0
     */
    public string $subtype = '';

    /**
     * Exchange id.
     *
     * @var int
     * @since 1.0.0
     */
    public int | InterfaceManager $exchange = 0;

    /**
     * Date type.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Created by account.
     *
     * @var int|Account
     * @since 1.0.0
     */
    public int | Account $createdBy = 0;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    /**
     * Get type.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param int $type Exchange type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setType(int $type) : void
    {
        $this->type = $type;
    }

    /**
     * Get id
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get fields.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getFields() : array
    {
        return $this->fields;
    }

    /**
     * Set fields.
     *
     * @param array $fields Exchange fields
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setFields(array $fields) : void
    {
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'        => $this->id,
            'message'   => $this->message,
            'type'      => $this->type,
            'fields'    => $this->fields,
            'createdAt' => $this->createdAt,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
