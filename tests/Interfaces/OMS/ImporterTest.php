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

use Modules\Exchange\Interfaces\OMS\Importer;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
final class ImporterTest extends \PHPUnit\Framework\TestCase
{
    private Importer $importer;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->importer = new Importer(new NullConnection(), new NullConnection(), new L11nManager('Api'));
    }

    /**
     * @covers Modules\Exchange\Interfaces\OMS\Importer
     * @group module
     */
    public function testLanguageImport() : void
    {
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('id', '123');
        $request->setData('type', 'language');

        if (!\is_file(__DIR__ . '/test_tmp.csv')) {
            \copy(__DIR__ . '/test.csv', __DIR__ . '/test_tmp.csv');
        }

        TestUtils::setMember($request, 'files', [
            'file0' => [
                'name'     => 'test.csv',
                'type'     => 'csv',
                'tmp_name' => __DIR__ . '/test_tmp.csv',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/test_tmp.csv'),
            ],
        ]);

        $export = $this->importer->importFromRequest($request);
        self::assertEquals(
            \date('Y-m-d'),
            \date('Y-m-d', \filemtime(__DIR__ . '/../../../../TestModule/Theme/Backend/Lang/en.lang.php'))
        );
    }
}
