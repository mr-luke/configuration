<?php

namespace Mrluke\Configuration;

use InvalidArgumentException;
use Mrluke\Configuration\Contracts\Schema as SchemaContract;

/**
 * Schema is a class that provide check for array.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 * @link      http://github.com/mr-luke/configuration
 * @license   MIT
 */
final class Schema implements SchemaContract
{
    /**
     * List of available rules.
     *
     * @var array
     */
    private $rules = ['required', 'nullable', 'boolean', 'float', 'integer', 'string'];

    /**
     * Schema for the configuration.
     *
     * @var array
     */
    private $schema;

    public function __construct(array $schema)
    {
        if (in_array(null, $schema)) {
            throw new InvalidArgumentException(
                'Bad Schema definition. Each key has to have rules described.'
            );
        }
        $this->schema = $schema;
    }

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
    public function check(array $insert, bool $throw = true): bool
    {
        foreach ($this->schema as $key => $rules) {
            // Check each key by given rules and respond
            // due to required flow.
            $result = $this->processRules($key, explode('|', $rules), $insert);

            if (!$result['status'] && $throw) {
                throw new InvalidArgumentException(
                    sprintf($result['message'], $key)
                );
            }
        }

        return $result['status'] ?? true;
    }

    /**
     * Create new Schema instance based on file.
     *
     * @param  string $path
     * @param  bool   $json
     * @return \Mrluke\Configuration\Schema
     */
    public static function createFromFile(string $path, bool $json = false): Schema
    {
        Schema::fileExists($path);

        $schema = $json ? json_decode(file_get_contents($path), true) : include_once $path;

        if (!is_array($schema)) {
            throw new InvalidArgumentException(
                '[createFromFile] method requires file that return a php array or json.'
            );
        }

        return new Schema($schema);
    }

    /**
     * Determine if given value is type boolean.
     *
     * @param  mixed $value
     * @return bool
     */
    private function checkRuleBoolean($value): bool
    {
        return is_bool($value);
    }

    /**
     * Determine if given value is type boolean.
     *
     * @param  mixed $value
     * @return bool
     */
    private function checkRuleFloat($value): bool
    {
        return is_float($value);
    }

    /**
     * Determine if given value is type boolean.
     *
     * @param  mixed $value
     * @return bool
     */
    private function checkRuleInteger($value): bool
    {
        return is_integer($value);
    }

    /**
     * Determine if given value is nullable.
     *
     * @param  mixed $value
     * @return bool
     */
    private function checkRuleNullable($value): bool
    {
        return true;
    }

    /**
     * Determine if given value is type boolean.
     *
     * @param  mixed $value
     * @return bool
     */
    private function checkRuleRequired($value): bool
    {
        return !is_null($value);
    }

    /**
     * Determine if given value is type boolean.
     *
     * @param  mixed $value
     * @return bool
     */
    private function checkRuleString($value): bool
    {
        return !(is_bool($value) || is_numeric($value));
    }

    /**
     * Check if file of given path exists.
     *
     * @param  string $path
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    private static function fileExists(string $path): void
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(
                sprintf('Following file %s does not exists.', $path)
            );
        }
    }

    /**
     * Return all messages for Exception.
     *
     * @return array
     */
    private function messages(): array
    {
        return [
            'required' => 'Key [%s] must be non-null.',
            'boolean'  => 'Key [%s] must be boolen type.',
            'float'    => 'Key [%s] must be float type.',
            'integer'  => 'Key [%s] must be integer type.',
            'string'   => 'Key [%s] must be string type.',
        ];
    }

    /**
     * Parse each rules for given key of insert.
     *
     * @param  string $key
     * @param  array  $rules
     * @param  array  $insert
     * @return array
     */
    private function processRules(string $key, array $rules, array $insert): array
    {
        $message = null;
        $status = true;

        if (!isset($insert[$key])) {
            $status  = false;
            $message = 'Schema key [%s] not present on insert.';
        }

        do {
            $r = array_shift($rules);
            $method = 'checkRule'. ucfirst($r);

            if (!in_array($r, $this->rules)) {
                // There's no rule defined and allowed.
                continue;
            }

            if (!$this->{$method}($insert[$key])) {
                // When given key is not valid we need to stop process
                // and set message related to validation error.
                $status  = false;
                $message = $this->messages()[$r];
                break;
            }

        } while (count($rules));

        return compact('status', 'message');
    }
}
