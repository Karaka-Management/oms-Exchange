<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\tests\Interfacs\OMS;

use Modules\Exchange\Interfaces\OMS\Exporter;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Uri\HttpUri;

/**
 * @internal
 */
final class ExporterTest extends \PHPUnit\Framework\TestCase
{
    private Exporter $exporter;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->exporter = new Exporter(new NullConnection(), new L11nManager('Api'));
    }

    /**
     * @covers Modules\Exchange\Interfaces\OMS\Exporter
     * @group module
     */
    public function testLanguageExport() : void
    {
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('id', '123');
        $request->setData('type', 'language');

        $export = $this->exporter->exportFromRequest($request);
        self::assertCount(4, $export);
        self::assertGreaterThan(100, \strlen($export['content']));
        self::assertEquals($export['content'], $this->exporter->export(new \DateTime('now'), new \DateTime('now'))['content']);
    }
}
