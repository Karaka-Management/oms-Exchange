<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Exchange\Interfaces\GSD\Model
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Exchange\Interfaces\GSD\Model;

use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Mapper class.
 *
 * @package Modules\Exchange\Interfaces\GSD\Model
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class GSDAddressMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'Nummer'             => ['name' => 'Nummer',     'type' => 'int',      'internal' => 'id'],
        'row_create_time'    => ['name' => 'row_create_time', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt'],
        'row_create_user'    => ['name' => 'row_create_user', 'type' => 'int',      'internal' => 'createdBy'],
        'Name1'              => ['name' => 'Name1',           'type' => 'string',   'internal' => 'name1'],
        'Name2'              => ['name' => 'Name2',           'type' => 'string',   'internal' => 'name2'],
        'Name3'              => ['name' => 'Name3',           'type' => 'string',   'internal' => 'name3'],
        'Ort'                => ['name' => 'Ort',             'type' => 'string',   'internal' => 'city'],
        'PLZ'                => ['name' => 'PLZ',             'type' => 'string',   'internal' => 'zip'],
        'Strasse'            => ['name' => 'Strasse',         'type' => 'string',   'internal' => 'street'],
        'Land'               => ['name' => 'Land',            'type' => 'string',   'internal' => 'country'],
        'Telefon'            => ['name' => 'Telefon',         'type' => 'string',   'internal' => 'phone'],
        'Fax'                => ['name' => 'Fax',         'type' => 'string',   'internal' => 'fax'],
        'EMail'              => ['name' => 'EMail',         'type' => 'string',   'internal' => 'email'],
        'InternetAdresse'    => ['name' => 'InternetAdresse',         'type' => 'string',   'internal' => 'website'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'Adressen';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $createdAt = 'row_create_time';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'Nummer';
}
