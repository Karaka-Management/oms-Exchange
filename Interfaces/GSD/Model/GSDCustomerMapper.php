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
final class GSDCustomerMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'row_id'            => ['name' => 'row_id',            'type' => 'int',               'internal' => 'id'],
        'row_create_time'   => ['name' => 'row_create_time',   'type' => 'DateTimeImmutable', 'internal' => 'createdAt'],
        'row_create_user'   => ['name' => 'row_create_user',   'type' => 'int',               'internal' => 'createdBy'],
        'Kundennummer'      => ['name' => 'Kundennummer',      'type' => 'string',            'internal' => 'number'],
        'Kundentyp'         => ['name' => 'Kundentyp',         'type' => 'string',            'internal' => 'customerType'],
        'Konto'             => ['name' => 'Konto',             'type' => 'int',               'internal' => 'account'],
        '_MatGuthabenKonto' => ['name' => '_MatGuthabenKonto', 'type' => 'int',               'internal' => 'materialAccount'],
        '_Rechtsform'       => ['name' => '_Rechtsform',       'type' => 'int',               'internal' => 'legalType'],
        'Sammelkonto'       => ['name' => 'Sammelkonto',       'type' => 'int',               'internal' => 'accountsReceivableAccount'],
        'Erloeskennzeichen' => ['name' => 'Erloeskennzeichen', 'type' => 'string',            'internal' => 'earningsIndicator'],
        'Info'              => ['name' => 'Info',              'type' => 'string',            'internal' => 'info'],
        'KreditLimitintern' => ['name' => 'KreditLimitintern', 'type' => 'float',             'internal' => 'creditlimit'],
        'EGUstId'           => ['name' => 'EGUstId',           'type' => 'string',            'internal' => 'egustid'],
        'Steuernummer'      => ['name' => 'Steuernummer',      'type' => 'string',            'internal' => 'taxid'],
        'BIC'               => ['name' => 'BIC',               'type' => 'string',            'internal' => 'bic'],
        'IBAN'              => ['name' => 'IBAN',              'type' => 'string',            'internal' => 'iban'],
        'MandatsRef'        => ['name' => 'MandatsRef',        'type' => 'string',            'internal' => 'bankRef'],
        'Verkaeufer'        => ['name' => 'Verkaeufer',        'type' => 'string',            'internal' => 'salesRep'],
        'AdressId'          => ['name' => 'AdressId',          'type' => 'int',               'internal' => 'addr'],
        'Auftragssperre'    => ['name' => 'Auftragssperre',    'type' => 'bool',              'internal' => 'isLocked'],
        '_Papierkorb'       => ['name' => '_Papierkorb',       'type' => 'bool',              'internal' => 'isBlocked'],
        'Auslauf'           => ['name' => 'Auslauf',           'type' => 'bool',              'internal' => 'isDiscontinued'],
        'Mahnsperre'        => ['name' => 'Mahnsperre',        'type' => 'bool',              'internal' => 'reminderBlock'],
        'Sammelrechnung'    => ['name' => 'Sammelrechnung',    'type' => 'bool',              'internal' => 'isMonthlyInvoice'],
        '_Partnernummer1'   => ['name' => '_Partnernummer1',   'type' => 'string',           'internal' => 'partner/1'],
        '_Partnernummer2'   => ['name' => '_Partnernummer2',   'type' => 'string',           'internal' => 'partner/2'],
        '_Partnernummer3'   => ['name' => '_Partnernummer3',   'type' => 'string',           'internal' => 'partner/3'],
        '_Partnernummer4'   => ['name' => '_Partnernummer4',   'type' => 'string',           'internal' => 'partner/4'],
        '_Partnernummer5'   => ['name' => '_Partnernummer5',   'type' => 'string',           'internal' => 'partner/5'],
        '_Partnernummer6'   => ['name' => '_Partnernummer6',   'type' => 'string',           'internal' => 'partner/6'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'addr' => [
            'mapper'   => GSDAddressMapper::class,
            'external' => 'AdressId',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'Kunden';

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
