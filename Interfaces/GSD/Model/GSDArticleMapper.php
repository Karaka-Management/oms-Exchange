<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
final class GSDArticleMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'row_id'               => ['name' => 'row_id',               'type' => 'int',      'internal' => 'id'],
        'row_create_time'      => ['name' => 'row_create_time',      'type' => 'DateTime', 'internal' => 'createdAt', 'readonly' => true],
        'row_create_user'      => ['name' => 'row_create_user',      'type' => 'int',      'internal' => 'createdBy', 'readonly' => true],
        'Artikelnummer'        => ['name' => 'Artikelnummer',        'type' => 'string',   'internal' => 'number'],
        'Info'        => ['name' => 'Info',        'type' => 'string',   'internal' => 'info'],
        'Artikelbezeichnung'   => ['name' => 'Artikelbezeichnung',   'type' => 'string',   'internal' => 'name1'],
        '_Artikelbezeichnung2' => ['name' => '_Artikelbezeichnung2', 'type' => 'string',   'internal' => 'name2'],
        '_Englisch1' => ['name' => '_Englisch1', 'type' => 'string',   'internal' => 'name1Eng'],
        '_Englisch2' => ['name' => '_Englisch2', 'type' => 'string',   'internal' => 'name2Eng'],
        'EUWarengruppe'        => ['name' => 'EUWarengruppe',        'type' => 'string',   'internal' => 'EUitemgroup'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'Artikel';

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
    protected static string $primaryField = 'row_id';
}
