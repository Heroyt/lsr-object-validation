<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
readonly class Regex implements Validator
{
    public function __construct(
        protected string  $pattern,
        protected ?string $message = null,
    ) {}

    public function validateValue(
        mixed           $value,
        string | object $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void {
        if (!is_string($value) || preg_match($this->pattern, $value) === false) {
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
                    'Value does not match pattern. (value: %s)',
                    $value
                );
        }
    }
}
