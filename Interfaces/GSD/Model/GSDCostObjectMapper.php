<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange\Interfaces\GSD\Model
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Interfaces\GSD\Model;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Exchange\Interfaces\GSD\Model
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class GSDCostObjectMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'ROW_ID'          => ['name' => 'ROW_ID',          'type' => 'int',               'internal' => 'id'],
        'row_create_time' => ['name' => 'row_create_time', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'row_create_user' => ['name' => 'row_create_user', 'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
        'KTR'             => ['name' => 'KTR',             'type' => 'string',            'internal' => 'costObject'],
        'Bezeichnung'     => ['name' => 'Bezeichnung',     'type' => 'string',            'internal' => 'description'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'FiKostentraeger';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'row_create_time';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='ROW_ID';
}
