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

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Exchange\Interfaces\GSD\Model
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class GSDSupplierMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'row_id'                 => ['name' => 'row_id',            'type' => 'int',      'internal' => 'id'],
        'row_create_time'        => ['name' => 'row_create_time',   'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'row_create_user'        => ['name' => 'row_create_user',   'type' => 'int',      'internal' => 'createdBy', 'readonly' => true],
        'LieferantenNummer'      => ['name' => 'LieferantenNummer',      'type' => 'string',   'internal' => 'number'],
        'Info'                   => ['name' => 'Info',              'type' => 'string',   'internal' => 'info'],
        'Auftragssperre'         => ['name' => 'Auftragssperre',    'type' => 'string',   'internal' => 'deliveryStatus'],
        'Steuernummer'           => ['name' => 'Steuernummer',      'type' => 'string',   'internal' => 'taxid'],
        'BIC'                    => ['name' => 'BIC',               'type' => 'string',   'internal' => 'bic'],
        'IBAN'                   => ['name' => 'IBAN',              'type' => 'string',   'internal' => 'iban'],
        'AdressId'               => ['name' => 'AdressId',              'type' => 'int',   'internal' => 'addr'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'addr' => [
            'mapper'     => GSDAddressMapper::class,
            'external'   => 'AdressId',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'Lieferanten';

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
    public const PRIMARYFIELD ='row_id';
}
