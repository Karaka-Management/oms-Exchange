<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Exchange\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class InterfaceManagerMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'exchange_id'      => ['name' => 'exchange_id',      'type' => 'int',    'internal' => 'id'],
        'exchange_title'   => ['name' => 'exchange_title',   'type' => 'string', 'internal' => 'info/name'],
        'exchange_path'    => ['name' => 'exchange_path',    'type' => 'string', 'internal' => 'path'],
        'exchange_version' => ['name' => 'exchange_version', 'type' => 'string', 'internal' => 'info/version'],
        'exchange_export'  => ['name' => 'exchange_export',  'type' => 'bool',   'internal' => 'info/export'],
        'exchange_import'  => ['name' => 'exchange_import',  'type' => 'bool',   'internal' => 'info/import'],
        'exchange_website' => ['name' => 'exchange_website', 'type' => 'string', 'internal' => 'info/website'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'exchange';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='exchange_id';
}
