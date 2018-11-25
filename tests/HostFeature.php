<?php

namespace Test;

use Mrluke\Configuration\Schema;
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
class HostFeature extends TestCase
{
    public function testReturnsValueWithSchema()
    {
        $schema = new Schema([
            'first' => 'required|integer'
        ]);

        $host = new Host([
            'first' => 10
        ], $schema);

        $this->assertEquals(10, $host->get('first'));
    }

    public function testThrowsExceptionOnInsertNotFollowingTheSchema()
    {
        $this->expectException(\InvalidArgumentException::class);

        $schema = new Schema([
            'first' => 'required|integer'
        ]);

        $host = new Host([
            'first' => 'Bad type'
        ], $schema);
    }
}
