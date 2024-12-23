<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Required implements Validator
{

    public function validateValue(mixed $value, string | object $class, string $property) : void {
        if (is_null($value)) {
            $this->throw($class, $property);
        }
    }

    /**
     * @param  object|class-string  $class
     * @param  string  $property
     * @return mixed
     */
    public function throw(object | string $class, string $property) {
        throw ValidationException::create(
            $class,
            $property,
            'Is required.'
        );
    }
}