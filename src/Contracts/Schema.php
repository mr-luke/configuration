<?php

namespace Mrluke\Configuration\Contracts;

/**
 * Schema is an interface for schema checker.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/configuration
 *
 * @license   MIT
 */
interface Schema
{
    /**
     * Check if given array is matching the schema.
     *
     * @param  array $insert
     * @param  bool  $throw
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public function check(array $insert, bool $throw = true):bool;
}
