<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use Modules\Job\Models\Job;

/**
 * Setting class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class ExchangeSetting implements \JsonSerializable
{
    /**
     * Article ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Data.
     *
     * @var array
     * @since 1.0.0
     */
    private array $data = [];

    /**
     * Job.
     *
     * @var Job
     * @since 1.0.0
     */
    private ?Job $job = null;

    /**
     * Job.
     *
     * @var int
     * @since 1.0.0
     */
    public int $exchange = 0;

    /**
     * Get data.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Set data.
     *
     * @param array $data Data
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setData(array $data) : void
    {
        $this->data = $data;
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
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'job'       => $this->job,
            'exchange'  => $this->exchange,
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
