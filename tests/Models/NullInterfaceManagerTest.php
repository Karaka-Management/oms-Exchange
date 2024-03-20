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

use Modules\Exchange\Models\NullInterfaceManager;

/**
 * @internal
 */
final class NullInterfaceManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \Modules\Exchange\Models\NullInterfaceManager
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Exchange\Models\InterfaceManager', new NullInterfaceManager());
    }

    /**
     * @covers \Modules\Exchange\Models\NullInterfaceManager
     * @group module
     */
    public function testId() : void
    {
        $null = new NullInterfaceManager(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers \Modules\Exchange\Models\NullInterfaceManager
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullInterfaceManager(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
