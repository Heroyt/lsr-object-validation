<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;
use Nette\Utils\Validators;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Url implements Validator
{
    public function validateValue(mixed $value, string | object $class, string $property): void {
        if (!is_string($value) || !Validators::isUrl($value)) {
            throw ValidationException::createWithValue(
                $class,
                $property,
                'Must be a valid URL. (value: %s)',
                $value
            );
        }
    }
}
