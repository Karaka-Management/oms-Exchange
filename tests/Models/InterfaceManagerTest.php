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
        self::assertEquals(0, $this->interface->getId());
        self::assertEquals('', $this->interface->getPath());
        self::assertEquals('', $this->interface->getName());
        self::assertEquals('', $this->interface->getInterfacePath());
        self::assertFalse($this->interface->hasImport());
        self::assertFalse($this->interface->hasExport());
        self::assertEquals([], $this->interface->get());
    }

    /**
     * @covers Modules\Exchange\Models\InterfaceManager
     * @group module
     */
    public function testLoadInputOutput() : void
    {
        $this->interface = new InterfaceManager(__DIR__ . '/testInterface.json');
        $this->interface->load();

        self::assertEquals(
            \json_decode(\file_get_contents(__DIR__ . '/testInterface.json'), true),
            $this->interface->get()
        );
    }

    /**
     * @covers Modules\Exchange\Models\InterfaceManager
     * @group module
     */
    public function testSetInputOutput() : void
    {
        $this->interface = new InterfaceManager(__DIR__ . '/testInterface.json');
        $this->interface->load();

        $this->interface->set('website', 'https://orange-management.org');
        self::assertEquals('https://orange-management.org', $this->interface->get()['website']);

        self::assertNotEquals(
            \json_decode(\file_get_contents(__DIR__ . '/testInterface.json'), true),
            $this->interface->get()
        );

        $this->interface->update();
        self::assertEquals(
            \json_decode(\file_get_contents(__DIR__ . '/testInterface.json'), true),
            $this->interface->get()
        );

        $this->interface->set('website', '');
        $this->interface->update();
    }

    /**
     * @covers Modules\Exchange\Models\InterfaceManager
     * @group module
     */
    public function testInvalidPathLoad() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);
        $this->interface->load();
    }

    /**
     * @covers Modules\Exchange\Models\InterfaceManager
     * @group module
     */
    public function testInvalidPathUpdate() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);
        $this->interface->update();
    }

    /**
     * @covers Modules\Exchange\Models\InterfaceManager
     * @group module
     */
    public function testInvalidDataSet() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->interface->set('test/path', new InterfaceManager());
    }
}
