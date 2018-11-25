<?php

namespace Mrluke\Configuration;

use InvalidArgumentException;
use Mrluke\Configuration\Contracts\ArrayHost;
use Mrluke\Configuration\Contracts\Schema;

/**
 * Configuration is a wrapper class provides array as object.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/configuration
 *
 * @license   MIT
 */
final class Host implements ArrayHost
{
    /**
     * Configuration asoc array.
     *
     * @var array
     */
    protected $config;

    function __construct(array $insert, Schema $schema = null)
    {
        if ($schema) {
            // Check if given insert Configuration
            // is following the Schema rules.
            //
            $schema->check($insert);
        }
        $this->config = $insert;
    }

    /**
     * Return given key from array.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $result = $this->iterateConfig($key);

        return is_null($result) ? $default : $result;
    }

    /**
     * Return of givent key is present.
     *
     * @param  string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        $result = $this->iterateConfig($key);

        return !($result === null);
    }

    /**
     * Magic getter.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key, null);
    }

    /**
     * Iterate through configuration.
     *
     * @param  string $key
     * @return mixed
     */
    protected function iterateConfig(string $key)
    {
        $result = $this->config;

        foreach (explode('.', $key) as $p) {
            if (!isset($result[$p])) {
                return null;
            }

            $result = $result[$p];
        }

        return $result;
    }
}
