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

use Modules\Exchange\Models\ExchangeLog;
use Modules\Exchange\Models\ExchangeType;

/**
 * @internal
 */
final class ExchangeLogTest extends \PHPUnit\Framework\TestCase
{
    private ExchangeLog $log;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->log = new ExchangeLog();
    }

    /**
     * @covers Modules\Exchange\Models\ExchangeLog
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->log->id);
        self::assertEquals('', $this->log->message);
        self::assertEquals('', $this->log->subtype);
        self::assertEquals(0, $this->log->exchange);
        self::assertEquals(0, $this->log->createdBy);
        self::assertEquals(ExchangeType::IMPORT, $this->log->getType());
        self::assertInstanceOf('\DateTimeImmutable', $this->log->createdAt);
    }

    /**
     * @covers Modules\Exchange\Models\ExchangeLog
     * @group module
     */
    public function testTypeInputOutput() : void
    {
        $this->log->setType(ExchangeType::EXPORT);
        self::assertEquals(ExchangeType::EXPORT, $this->log->getType());
    }

    /**
     * @covers Modules\Exchange\Models\ExchangeLog
     * @group module
     */
    public function testFieldsInputOutput() : void
    {
        $this->log->setFields($fields = [
            'name'  => 'test',
            'start' => 'now',
        ]);
        self::assertEquals($fields, $this->log->getFields());
    }

    /**
     * @covers Modules\Exchange\Models\ExchangeLog
     * @group module
     */
    public function testSerialize() : void
    {
        $this->log->message = '123456';
        $this->log->setType(ExchangeType::EXPORT);

        $serialized = $this->log->jsonSerialize();
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'      => 0,
                'message' => '123456',
                'type'    => ExchangeType::EXPORT,
                'fields'  => [],
            ],
            $serialized
        );
    }
}
