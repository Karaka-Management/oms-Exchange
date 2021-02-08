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
use Modules\Admin\Models\AccountMapper;

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
        'exchange_log_subtype'    => ['name' => 'exchange_log_subtype', 'type' => 'string',    'internal' => 'subtype'],
        'exchange_log_created_at'    => ['name' => 'exchange_log_created_at', 'type' => 'DateTimeImmutable',    'internal' => 'createdAt', 'readonly' => true],
        'exchange_log_created_by'    => ['name' => 'exchange_log_created_by', 'type' => 'int',    'internal' => 'createdBy', 'readonly' => true],
        'exchange_log_exchange'    => ['name' => 'exchange_log_exchange', 'type' => 'int',    'internal' => 'exchange'],
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

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, external:string}>
     * @since 1.0.0
     */
    protected static array $belongsTo = [
        'createdBy' => [
            'mapper'     => AccountMapper::class,
            'external'   => 'exchange_log_created_by',
        ],
        'exchange' => [
            'mapper'     => InterfaceManagerMapper::class,
            'external'   => 'exchange_log_exchange',
        ],
    ];
}
