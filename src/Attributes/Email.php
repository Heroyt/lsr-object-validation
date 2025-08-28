<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;
use Nette\Utils\Validators;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
readonly class Email implements Validator
{

    public function __construct(
        protected ?string $message = null,
        protected bool $allowEmpty = false,
    ) {}

    public function validateValue(
        mixed           $value,
        string | object $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void {
        if ($this->allowEmpty && empty($value)) {
            return;
        }
        if (!is_string($value) || !Validators::isEmail($value)) {
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
                    'Must be a valid email. (value: %s)',
                    $value
                );
        }
    }
}
