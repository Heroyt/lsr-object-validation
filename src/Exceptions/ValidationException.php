<?php

namespace Lsr\ObjectValidation\Exceptions;

use JsonException;
use RuntimeException;

class ValidationException extends RuntimeException
{
    /** @var class-string|null */
    public private(set) ?string $object = null;
    public private(set) ?string $property = null;


    /**
     * @param  object|class-string  $object
     * @param  non-empty-string  $property
     * @param  string  $message
     * @param  mixed  $value
     * @return ValidationException
     */
    public static function createWithValue(
        object | string $object,
        string          $property,
        string          $message,
        mixed           $value
    ) : ValidationException {
        try {
            $formatted = json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $formatted = print_r($value, true);
        }

        $object = is_string($object) ? $object : get_class($object);

        return
            new self(
                sprintf(
                    "%s::\$%s - ".$message,
                    $object,
                    $property,
                    $formatted
                )
            )->withObject($object)
             ->withProperty($property);
    }


    /**
     * @param  object|class-string  $object
     * @param  non-empty-string  $property
     * @param  string  $message
     * @return ValidationException
     */
    public static function create(
        object | string $object,
        string          $property,
        string          $message
    ) : ValidationException {
        $object = is_string($object) ? $object : get_class($object);

        return
            new self(
                sprintf(
                    "%s::\$%s - ".$message,
                    $object,
                    $property
                )
            )->withObject($object)
             ->withProperty($property);
    }

    /**
     * @param  object|class-string  $object
     * @param  non-empty-string  $property
     * @param  string  $message
     * @param  mixed  $value
     * @return ValidationException
     */
    public static function createWithCustomMessage(
        object | string $object,
        string          $property,
        string          $message,
        mixed           $value = null
    ) : ValidationException {
        $object = is_string($object) ? $object : get_class($object);

        try {
            $formatted = json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $formatted = print_r($value, true);
        }

        return
            new self(
                str_contains($message, '%s') ?
                    sprintf($message, $formatted)
                    : $message
            )
                ->withObject($object)
                ->withProperty($property);
    }

    public function withProperty(string $property) : ValidationException {
        $this->property = $property;
        return $this;
    }

    /**
     * @param  class-string  $object
     * @return $this
     */
    public function withObject(string $object) : ValidationException {
        $this->object = $object;
        return $this;
    }
}
