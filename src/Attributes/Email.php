<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;
use Nette\Utils\Validators;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Email implements Validator
{
    public function validateValue(
        mixed           $value,
        string | object $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void {
        if (!is_string($value) || !Validators::isEmail($value)) {
            throw ValidationException::createWithValue(
                $class,
                $propertyPrefix.$property,
                'Must be a valid email. (value: %s)',
                $value
            );
        }
    }
}
