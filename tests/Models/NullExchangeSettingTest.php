<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\tests\Models;

use Modules\Exchange\Models\NullExchangeSetting;

/**
 * @internal
 */
final class NullExchangeSettingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Exchange\Models\NullExchangeSetting
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Exchange\Models\ExchangeSetting', new NullExchangeSetting());
    }

    /**
     * @covers Modules\Exchange\Models\NullExchangeSetting
     * @group module
     */
    public function testId() : void
    {
        $null = new NullExchangeSetting(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\Exchange\Models\NullExchangeSetting
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullExchangeSetting(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
