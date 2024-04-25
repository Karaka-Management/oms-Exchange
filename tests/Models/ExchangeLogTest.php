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

use Modules\Exchange\Models\ExchangeLog;
use Modules\Exchange\Models\ExchangeType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Exchange\Models\ExchangeLog::class)]
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->log->id);
        self::assertEquals('', $this->log->message);
        self::assertEquals('', $this->log->subtype);
        self::assertEquals(0, $this->log->exchange);
        self::assertEquals(0, $this->log->createdBy->id);
        self::assertEquals(ExchangeType::IMPORT, $this->log->type);
        self::assertInstanceOf('\DateTimeImmutable', $this->log->createdAt);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testFieldsInputOutput() : void
    {
        $this->log->setFields($fields = [
            'name'  => 'test',
            'start' => 'now',
        ]);
        self::assertEquals($fields, $this->log->getFields());
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testSerialize() : void
    {
        $this->log->message = '123456';
        $this->log->type    = ExchangeType::EXPORT;

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
