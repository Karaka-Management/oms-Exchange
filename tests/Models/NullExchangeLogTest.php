<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\tests\Models;

use Modules\Exchange\Models\NullExchangeLog;

/**
 * @internal
 */
final class NullExchangeLogTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Exchange\Models\NullExchangeLog
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Exchange\Models\ExchangeLog', new NullExchangeLog());
    }

    /**
     * @covers Modules\Exchange\Models\NullExchangeLog
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullExchangeLog(2);
        self::assertEquals(2, $null->getId());
    }
}
