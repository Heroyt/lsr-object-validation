<?php

declare(strict_types=1);

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class IntRange implements Validator
{
    public function __construct(
        public ?int $min = null,
        public ?int $max = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validateValue(
        mixed           $value,
        object | string $class,
        string          $property,
        string          $propertyPrefix = ''
    ) : void {
        if (!is_int($value)) {
            throw ValidationException::createWithValue(
                $class,
                $propertyPrefix.$property,
                'Must be an int. (value: %s)',
                $value
            );
        }

        if ($this->min !== null && $value < $this->min) {
            throw ValidationException::createWithValue(
                $class,
                $propertyPrefix.$property,
                'Number must be larger then '.$this->min.'. (value: %s)',
                $value
            );
        }
        if ($this->max !== null && $value > $this->max) {
            throw ValidationException::createWithValue(
                $class,
                $propertyPrefix.$property,
                'Number must be lower then '.$this->max.'. (value: %s)',
                $value
            );
        }
    }
}
