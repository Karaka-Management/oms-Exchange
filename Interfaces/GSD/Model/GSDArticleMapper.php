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
        'row_id'                         => ['name' => 'row_id',                  'type' => 'int',      'internal' => 'id'],
        'row_create_time'                => ['name' => 'row_create_time',         'type' => 'DateTimeImmutable', 'internal' => 'createdAt'],
        'row_create_user'                => ['name' => 'row_create_user',         'type' => 'int',      'internal' => 'createdBy'],
        'Artikelnummer'                  => ['name' => 'Artikelnummer',          'type' => 'string',   'internal' => 'number'],
        'Auslaufartikel'                 => ['name' => 'Auslaufartikel',          'type' => 'bool',   'internal' => 'isDiscontinued'],
        '_Artikelsperre'                 => ['name' => '_Artikelsperre',          'type' => 'bool',   'internal' => 'isBlocked'],
        'ManuelleChargenEntnahme'        => ['name' => 'ManuelleChargenEntnahme', 'type' => 'bool',   'internal' => 'manualLotUse'],
        'Chargenverwaltung'              => ['name' => 'Chargenverwaltung',       'type' => 'string',   'internal' => 'lotManagement'],
        'Seriennummernvergabe'           => ['name' => 'Seriennummernvergabe',    'type' => 'bool',   'internal' => 'hasSN'],
        '_Minusbestand'                  => ['name' => '_Minusbestand',           'type' => 'bool',   'internal' => 'negativeStock'],
        '_Exportartikel'                 => ['name' => '_Exportartikel',          'type' => 'bool',   'internal' => 'exportItem'],
        '_DrittlandArtikel'              => ['name' => '_DrittlandArtikel',       'type' => 'bool',   'internal' => 'nonEUItem'],
        '_DualUse'                       => ['name' => '_DualUse',                'type' => 'bool',   'internal' => 'dualUse'],
        'EkEinheit'                      => ['name' => 'EkEinheit',               'type' => 'string',   'internal' => 'purchaseUnit'],
        'Gewicht'                        => ['name' => 'Gewicht',                 'type' => 'float',   'internal' => 'weight'],
        'Hoehe'                          => ['name' => 'Hoehe',                   'type' => 'float',   'internal' => 'height'],
        'Laenge'                         => ['name' => 'Laenge',                  'type' => 'float',   'internal' => 'length'],
        'Volumen'                        => ['name' => 'Volumen',                 'type' => 'float',   'internal' => 'volume'],
        'Mindestbestand'                 => ['name' => 'Mindestbestand',          'type' => 'float',   'internal' => 'minimalStock'],
        'BeschaffungszeitWochen'         => ['name' => 'BeschaffungszeitWochen',  'type' => 'int',      'internal' => 'leadTimeWeeks'],
        'BeschaffungszeitTage'           => ['name' => 'BeschaffungszeitTage',    'type' => 'int',      'internal' => 'leadTimeDays'],
        '_InfoVerkauf'                   => ['name' => '_InfoVerkauf',            'type' => 'string',   'internal' => 'infoSales'],
        '_InfoEinkauf'                   => ['name' => '_InfoEinkauf',            'type' => 'string',   'internal' => 'infoPurchase'],
        '_LagerInfo'                     => ['name' => '_LagerInfo',              'type' => 'string',   'internal' => 'infoWarehouse'],
        'WebShop'                        => ['name' => 'WebShop',                 'type' => 'int',   'internal' => 'inShop'],
        'Artikelbezeichnung'             => ['name' => 'Artikelbezeichnung',      'type' => 'string',   'internal' => 'name1'],
        '_Artikelbezeichnung2'           => ['name' => '_Artikelbezeichnung2',    'type' => 'string',   'internal' => 'name2'],
        '_Englisch1'                     => ['name' => '_Englisch1',              'type' => 'string',   'internal' => 'name1Eng'],
        '_Englisch2'                     => ['name' => '_Englisch2',              'type' => 'string',   'internal' => 'name2Eng'],
        'EUWarengruppe'                  => ['name' => 'EUWarengruppe',           'type' => 'string',   'internal' => 'EUitemgroup'],
        'zolltarifnr'                    => ['name' => 'zolltarifnr',             'type' => 'string',   'internal' => 'customsId'],
        '_UNNummer'                      => ['name' => '_UNNummer',               'type' => 'string',   'internal' => 'unnumber'],
        '_Pruefabteilung'                => ['name' => '_Pruefabteilung',         'type' => 'string',   'internal' => 'inspectionDepartment'],
        '_MedizinProduktklasse'          => ['name' => '_MedizinProduktklasse',         'type' => 'string',   'internal' => 'medicinProductClass'],
        '_Sparte'                        => ['name' => '_Sparte',                 'type' => 'string',   'internal' => 'sectionGroup'],
        '_Umsatzgruppe'                  => ['name' => '_Umsatzgruppe',           'type' => 'string',   'internal' => 'salesGroup'],
        '_Segment'                       => ['name' => '_Segment',                'type' => 'string',   'internal' => 'segment'],
        '_Produktgruppe'                 => ['name' => '_Produktgruppe',                'type' => 'int',   'internal' => 'productGroup'],
        'Erloeskennzeichen'              => ['name' => 'Erloeskennzeichen',       'type' => 'string',   'internal' => 'earningsIndicator'],
        'Kostenkennzeichen'              => ['name' => 'Kostenkennzeichen',       'type' => 'string',   'internal' => 'costsIndicator'],
        '_GewichtWeissblech'             => ['name' => '_GewichtWeissblech',       'type' => 'float',   'internal' => 'weightTinplate'],
        '_GewichtSonstigeVerbunde'       => ['name' => '_GewichtSonstigeVerbunde',       'type' => 'float',   'internal' => 'weightOtherComposites'],
        '_GewichtSonstiges'              => ['name' => '_GewichtSonstiges',       'type' => 'float',   'internal' => 'weightOther'],
        '_GewichtPET'                    => ['name' => '_GewichtPET',       'type' => 'float',   'internal' => 'weightPET'],
        '_GewichtPapier'                 => ['name' => '_GewichtPapier',       'type' => 'float',   'internal' => 'weightPaper'],
        '_GewichtNaturmaterialien'       => ['name' => '_GewichtNaturmaterialien',       'type' => 'float',   'internal' => 'weightNatureProducts'],
        '_GewichtKunststoff'             => ['name' => '_GewichtKunststoff',       'type' => 'float',   'internal' => 'weightAcrylics'],
        '_GewichtKartonverbunde'         => ['name' => '_GewichtKartonverbunde',       'type' => 'float',   'internal' => 'weightCarton'],
        '_GewichtGlas'                   => ['name' => '_GewichtGlas',       'type' => 'float',   'internal' => 'weightGlas'],
        '_GewichtAluminium'              => ['name' => '_GewichtAluminium',       'type' => 'float',   'internal' => 'weightAluminium'],
        '_GewichtBrutto'                 => ['name' => '_GewichtBrutto',       'type' => 'float',   'internal' => 'weightGross'],
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
