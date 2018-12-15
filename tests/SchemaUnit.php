<?php

namespace Test;

use Mrluke\Configuration\Contracts\Schema as Contract;
use Mrluke\Configuration\Schema;
use PHPUnit\Framework\TestCase;

/**
 * UnitTests for Schema class.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/configuration
 *
 * @license   MIT
 */
class SchemaUnit extends TestCase
{

    public function testFollowsTheContract()
    {
        $schema = new Schema([
            'first' => 'required|string',
        ]);

        $this->assertTrue($schema instanceof Contract);
    }

    public function testThrowsIfArrayWithoutDefinition()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Schema([
            'first' => ''
        ]);
    }

    public function testReturnTrueOnValidInsert()
    {
        $schema = new Schema([
            'first' => 'required|integer'
        ]);

        $this->assertTrue($schema->check([
            'first' => 10
        ]));
    }

    public function testReturnFalseOnInvalidInsertWithThrowsOff()
    {
        $schema = new Schema([
            'first' => 'required|integer'
        ]);

        $this->assertTrue(!$schema->check([
            'first' => 'bad'
        ], false));
    }

    public function testThrowsExceptionOnInvalidInsert()
    {
        $this->expectException(\InvalidArgumentException::class);

        $schema = new Schema([
            'first' => 'required|integer'
        ]);

        $schema->check([
            'first' => 'bad'
        ]);
    }

    public function testThrowsexceptionOnPartlyInvalidInsert()
    {
        $this->expectException(\InvalidArgumentException::class);

        $schema = new Schema([
            'first'  => 'required|integer',
            'second' => 'required'
        ]);

        $schema->check([
            'first'  => 10,
            'second' => null
        ]);
    }

    public function testLoadArrayFromFile()
    {
        $file = __DIR__.'/../testArray.php';

        $schema = Schema::createFromFile($file);

        $this->assertTrue($schema->check([
            'first' => 10
        ]));
    }

    public function testLoadJsonFromFile()
    {
        $file = __DIR__.'/../testJson.json';

        $schema = Schema::createFromFile($file, true);

        $this->assertTrue($schema->check([
            'first' => 10
        ]));
    }
}
