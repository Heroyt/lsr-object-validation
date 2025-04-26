<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
readonly class Numeric implements Validator
{
    public function __construct(
        protected ?string $message = null,
    ) {}

    public function validateValue(
        mixed           $value,
        string | object $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void {
        if (empty($value) || !is_numeric($value)) {
            throw $this->message !== null ?
                ValidationException::createWithCustomMessage(
                    $class,
                    $property,
                    $this->message,
                    $value
                )
                : ValidationException::createWithValue(
                $class,
                $propertyPrefix.$property,
                'Must be numeric (string, int or float). (value: %s)',
                $value,
            );
        }
    }
}
