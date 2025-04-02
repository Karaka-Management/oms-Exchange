<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\tests\Models;

use Modules\Exchange\Models\ExporterAbstract;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;

/**
 * @internal
 */
final class ExporterAbstractTest extends \PHPUnit\Framework\TestCase
{
    private ExporterAbstract $class;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->class = new class(
            new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']),
            new NullConnection(),
            new L11nManager('placeholder')
        ) extends ExporterAbstract {
            public function exportFromRequest(RequestAbstract $request, ResponseAbstract $response) : array
            {
                return [$this->local, $this->l11n];
            }
        };
    }

    public function testMembers() : void
    {
        $result = $this->class->exportFromRequest(new HttpRequest(), new HttpResponse());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\ConnectionAbstract', $result[0]);
        self::assertInstanceOf(L11nManager::class, $result[1]);
    }
}
