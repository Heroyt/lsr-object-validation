<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Numeric implements Validator
{
    public function validateValue(
        mixed           $value,
        string | object $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void {
        if (empty($value) || !is_numeric($value)) {
            throw ValidationException::createWithValue(
                $class,
                $propertyPrefix.$property,
                'Must be numeric (string, int or float). (value: %s)',
                $value,
            );
        }
    }
}
