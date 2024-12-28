<?php

namespace Lsr\ObjectValidation\Attributes;

use Lsr\ObjectValidation\Exceptions\ValidationException;

interface Validator
{
    /**
     * Validate a value and throw an exception on error
     *
     * @param  mixed  $value
     * @param  class-string|object  $class
     * @param  non-empty-string  $property
     * @param  string  $propertyPrefix
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function validateValue(
        mixed           $value,
        string | object $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void;
}
