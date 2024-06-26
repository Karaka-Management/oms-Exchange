<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\tests\Models;

use Modules\Exchange\Models\ImporterAbstract;
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
final class ImporterAbstractTest extends \PHPUnit\Framework\TestCase
{
    private ImporterAbstract $class;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->class = new class(
            new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']),
            new NullConnection(),
            new L11nManager('placeholder')
        ) extends ImporterAbstract {
            public function importFromRequest(RequestAbstract $request, ResponseAbstract $response) : array
            {
                return [$this->local, $this->remote, $this->l11n];
            }
        };
    }

    public function testMembers() : void
    {
        $result = $this->class->importFromRequest(new HttpRequest(), new HttpResponse());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\ConnectionAbstract', $result[0]);
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\ConnectionAbstract', $result[1]);
        self::assertInstanceOf(L11nManager::class, $result[2]);
    }
}
