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

use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Exchange log mapper class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ExchangeLogMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'exchange_log_id'      => ['name' => 'exchange_log_id',    'type' => 'int',    'internal' => 'id'],
        'exchange_log_message' => ['name' => 'exchange_log_message', 'type' => 'string', 'internal' => 'message'],
        'exchange_log_fields'  => ['name' => 'exchange_log_fields',  'type' => 'Json',    'internal' => 'fields'],
        'exchange_log_type'    => ['name' => 'exchange_log_type', 'type' => 'int',    'internal' => 'type'],
        'exchange_log_time'    => ['name' => 'exchange_log_time', 'type' => 'DateTimeImmutable',    'internal' => 'createdAt'],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $model = ExchangeLog::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'exchange_log';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'exchange_log_id';
}
