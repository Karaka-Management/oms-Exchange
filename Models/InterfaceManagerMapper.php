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

use Modules\Admin\Models\AccountMapper;
use Modules\Media\Models\CollectionMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * InterfaceManager mapper class.
 *
 * @package Modules\Exchange\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of InterfaceManager
 * @extends DataMapperFactory<T>
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
        'exchange_interface_id'         => ['name' => 'exchange_interface_id',      'type' => 'int',    'internal' => 'id'],
        'exchange_interface_title'      => ['name' => 'exchange_interface_title',   'type' => 'string', 'internal' => 'title'],
        'exchange_interface_version'    => ['name' => 'exchange_interface_version', 'type' => 'string', 'internal' => 'version'],
        'exchange_interface_export'     => ['name' => 'exchange_interface_export',  'type' => 'bool',   'internal' => 'hasExport'],
        'exchange_interface_import'     => ['name' => 'exchange_interface_import',  'type' => 'bool',   'internal' => 'hasImport'],
        'exchange_interface_website'    => ['name' => 'exchange_interface_website', 'type' => 'string', 'internal' => 'website'],
        'exchange_interface_media'      => ['name' => 'exchange_interface_media',       'type' => 'int',   'internal' => 'source'],
        'exchange_interface_created_at' => ['name' => 'exchange_interface_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'exchange_interface_created_by' => ['name' => 'exchange_interface_created_by', 'type' => 'int', 'internal' => 'createdBy', 'readonly' => true],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'exchange_interface';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'exchange_interface_id';

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'createdBy' => [
            'mapper'   => AccountMapper::class,
            'external' => 'exchange_interface_created_by',
        ],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'source' => [
            'mapper'   => CollectionMapper::class,
            'external' => 'exchange_interface_media',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'settings' => [
            'mapper'   => ExchangeSettingMapper::class,
            'table'    => 'exchange_settings',
            'self'     => 'exchange_settings_exchange',
            'external' => null,
        ],
    ];
}
