<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;
use Nette\Utils\Validators;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Uri implements Validator
{
    public function validateValue(mixed $value, string | object $class, string $property): void {
        if (!is_string($value) || !Validators::isUri($value)) {
            throw ValidationException::createWithValue(
                $class,
                $property,
                'Must be a valid URI. (value: %s)',
                $value
            );
        }
    }
}
