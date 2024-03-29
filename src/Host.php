<?php

namespace Mrluke\Configuration;

use Mrluke\Configuration\Contracts\ArrayHost;
use Mrluke\Configuration\Contracts\Schema as SchemaContract;

/**
 * Configuration is a wrapper class provides array as object.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 * @license   MIT
 * @link      http://github.com/mr-luke/configuration
 */
final class Host implements ArrayHost
{
    /**
     * Configuration asoc array.
     *
     * @var array
     */
    protected $config;

    public function __construct(array $insert, SchemaContract $schema = null)
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
     * @param string $key
     * @param mixed  $default
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
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        $result = $this->iterateConfig($key);

        return $result !== null;
    }

    /**
     * Return array of configuration.
     *
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this->config;
    }

    /**
     * Magic getter.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Iterate through configuration.
     *
     * @param string $key
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
