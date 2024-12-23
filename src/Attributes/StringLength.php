<?php

namespace Lsr\ObjectValidation\Attributes;

use Attribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
/**
 * Checks a string property's length
 */
class StringLength implements Validator
{
    /**
     * @param  positive-int|null  $length
     * @param  positive-int|null  $min
     * @param  positive-int|null  $max
     */
    public function __construct(
        public ?int $length = null,
        public ?int $min = null,
        public ?int $max = null,
    ) {
        assert(!isset($length) || (!isset($min) && !isset($max)), 'Cannot combine length argument with min and max.');
    }

    public function validateValue(mixed $value, string | object $class, string $property): void {
        if (!is_string($value)) {
            throw ValidationException::createWithValue(
                $class,
                $property,
                'Must be a string. (value: %s)',
                $value
            );
        }
        if ($this->length === null && $this->max === null && $this->min === null) {
            return;
        }

        $len = mb_strlen($value, 'UTF-8');

        if (isset($this->length) && $len !== $this->length) {
            throw ValidationException::createWithValue(
                $class,
                $property,
                'String length must be exactly ' . $this->length . '. (value: %s)',
                $value
            );
        }

        if (isset($this->min, $this->max) && ($len < $this->min || $len > $this->max)) {
            throw ValidationException::createWithValue(
                $class,
                $property,
                'String length must be between ' . $this->min . ' and ' . $this->max . '. (value: %s)',
                $value
            );
        }

        if (!isset($this->min) && isset($this->max) && $len > $this->max) {
            throw ValidationException::createWithValue(
                $class,
                $property,
                'String length must be at most ' . $this->max . '. (value: %s)',
                $value
            );
        }

        if (isset($this->min) && $len < $this->min) {
            throw ValidationException::createWithValue(
                $class,
                $property,
                'String length must be at least ' . $this->min . '. (value: %s)',
                $value
            );
        }
    }
}
