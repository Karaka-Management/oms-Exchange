<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Exchange setting mapper class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of ExchangeSetting
 * @extends DataMapperFactory<T>
 */
final class ExchangeSettingMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'exchange_settings_id'        => ['name' => 'exchange_settings_id',         'type' => 'int',               'internal' => 'id'],
        'exchange_settings_title'     => ['name' => 'exchange_settings_title',     'type' => 'string',              'internal' => 'title'],
        'exchange_settings_data'      => ['name' => 'exchange_settings_data',    'type' => 'Json',            'internal' => 'data'],
        'exchange_settings_relations' => ['name' => 'exchange_settings_relations',    'type' => 'Json',            'internal' => 'relations'],
        'exchange_settings_exchange'  => ['name' => 'exchange_settings_exchange',   'type' => 'int',               'internal' => 'exchange'],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = ExchangeSetting::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'exchange_settings';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'exchange_settings_id';
}
