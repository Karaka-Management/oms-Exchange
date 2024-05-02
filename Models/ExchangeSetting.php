<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use Modules\Job\Models\Job;

/**
 * Setting class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
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
    public int $id = 0;

    /**
     * Title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Relation definitions between tables/columns.
     *
     * @var array
     * @since 1.0.0
     */
    public array $relations = [];

    /**
     * Data.
     *
     * @var array
     * @since 1.0.0
     */
    public array $data = [];

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
     * Set relations
     *
     * @param array $relations Relations between tables/columns
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setRelations(array $relations) : void
    {
        $this->relations = $relations;
    }

    /**
     * Get table/column relations
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getRelations() : array
    {
        return $this->relations;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'exchange' => $this->exchange,
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
