<?php
/**
 * Karaka
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

use Modules\Exchange\Models\InterfaceManager;

/**
 * @internal
 */
final class InterfaceManagerTest extends \PHPUnit\Framework\TestCase
{
    private InterfaceManager $interface;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->interface = new InterfaceManager();
    }

    /**
     * @covers Modules\Exchange\Models\InterfaceManager
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->interface->id);
        self::assertEquals('', $this->interface->getPath());
        self::assertEquals('', $this->interface->title);
        self::assertFalse($this->interface->hasImport);
        self::assertFalse($this->interface->hasExport);
        self::assertEquals([], $this->interface->getSettings());
    }
}
