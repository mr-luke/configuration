<?php

namespace Test;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mrluke\Configuration\Contracts\ArrayHost as Contract;
use Mrluke\Configuration\Contracts\Schema;
use Mrluke\Configuration\Host;
use PHPUnit\Framework\TestCase;

/**
 * UnitTests for Host class.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/configuration
 *
 * @license   MIT
 */
class HostUnit extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testFollowsTheContract()
    {
        $host = new Host([
            'first' => null,
        ]);

        $this->assertTrue($host instanceof Contract);
    }

    public function testHasMethodReturnsBool()
    {
        $host = new Host([
            'first'  => 'value',
            'second' => 'value',
        ]);

        $this->assertTrue($host->has('first'));
        $this->assertTrue($host->has('second'));
        $this->assertTrue(!$host->has('third'));
    }

    public function testGetsReturnsValue()
    {
        $host = new Host([
            'first'  => 'first value',
            'second' => 1000
        ]);

        $this->assertEquals(
            'first value',
            $host->get('first')
        );

        $this->assertEquals(
            1000,
            $host->get('second')
        );
    }

    public function testGetsDefaultvalue()
    {
        $host = new Host([
            'first' => 'has value',
            'third' => 'looks like second is missing'
        ]);

        $this->assertEquals(
            'my default value',
            $host->get('second', 'my default value')
        );
    }

    public function testGetsNullWhenDefaultNotSpecified()
    {
        $host = new Host([
            'first' => 'key exists'
        ]);

        $value = $host->get('second');

        $this->assertTrue(is_null($value));
    }

    public function testCorrectSchema()
    {
        $insert = [
            'first' => 25,50
        ];

        $schemaMock = Mockery::mock('FakeSchema', Schema::class);
        $schemaMock->shouldReceive('check')->once()
                   ->with($insert)->andReturn(true);

        $host = new Host($insert, $schemaMock);

        $this->assertTrue($host instanceof Contract);
    }
}
