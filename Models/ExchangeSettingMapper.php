<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Exchange setting mapper class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
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
        'exchange_settings_id'         => ['name' => 'exchange_settings_id',         'type' => 'int',               'internal' => 'id'],
        'exchange_settings_title'      => ['name' => 'exchange_settings_title',     'type' => 'string',              'internal' => 'title'],
        'exchange_settings_data'       => ['name' => 'exchange_settings_data',    'type' => 'Json',            'internal' => 'data'],
        'exchange_settings_job'        => ['name' => 'exchange_settings_job',   'type' => 'int',               'internal' => 'job'],
        'exchange_settings_exchange'   => ['name' => 'exchange_settings_exchange',   'type' => 'int',               'internal' => 'exchange'],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var string
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
    public const PRIMARYFIELD ='exchange_settings_id';
}
