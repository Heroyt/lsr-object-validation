<?php
declare(strict_types=1);

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use DateTime;
use Lsr\ObjectValidation\Exceptions\ValidationException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
readonly class DateString implements Validator
{

    public function __construct(
        public ?string $format = null,
        public ?string $message = null,
    ) {}


    public function validateValue(
        mixed           $value,
        object | string $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void {
        if (!is_string($value)) {
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
                    'Must be a string. (value: %s)',
                    $value
                );
        }

        if ($this->format !== null) {
            if (!DateTime::createFromFormat($this->format, $value)) {
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
                        'Must be a valid date string. (value: %s)',
                        $value
                    );
            }
        }
        else if (!strtotime($value)) {
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
                    'Must be a valid date string. (value: %s)',
                    $value
                );
        }
    }
}