<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
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
     * @covers Modules\Exchange\Models\NullInterfaceManager
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Exchange\Models\InterfaceManager', new NullInterfaceManager());
    }

    /**
     * @covers Modules\Exchange\Models\NullInterfaceManager
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullInterfaceManager(2);
        self::assertEquals(2, $null->getId());
    }
}
