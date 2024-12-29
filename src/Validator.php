<?php

declare(strict_types=1);

namespace Lsr\ObjectValidation;

use Lsr\ObjectValidation\Attributes\Required;
use Lsr\ObjectValidation\Attributes\Validator as ValidatorAttribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;
use Lsr\ObjectValidation\Exceptions\ValidationMultiException;
use ReflectionAttribute;
use ReflectionClass;

class Validator
{

    /**
     * Holds (object+propertyPrefix) keys to detect infinite recursion.
     *
     * @var array<string,bool>
     */
    private array $processing = [];

    /**
     * @param  object  $object
     * @return void
     * @throws ValidationException
     */
    public function validate(object $object, string $propertyPrefix = '') : void {
        // Check recursion
        if (!$this->checkRecursion($object, $propertyPrefix)) {
            // If we're already processing this combination, skip.
            return;
        }

        try {
            $class = new ReflectionClass($object);
            $properties = $class->getProperties();
            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $type = $property->getType();
                $value = $property->isInitialized($object) ? $property->getValue($object) : null;

                $attributes = $property->getAttributes(ValidatorAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
                foreach ($attributes as $attributeReflection) {
                    /** @var ValidatorAttribute $attribute */
                    $attribute = $attributeReflection->newInstance();
                    if (!$property->isInitialized($object) || ($value === null && $type?->allowsNull())) {
                        if ($attribute instanceof Required) {
                            $attribute->throw($object, $propertyPrefix.$propertyName);
                        }
                    }
                    else {
                        $attribute->validateValue($value, $object, $propertyName, $propertyPrefix);
                    }
                }

                if (is_object($value)) {
                    // Recursive validation
                    $this->validate($value, $propertyPrefix.$propertyName.'.');
                }
            }
        } finally {
            $this->endRecursion($object, $propertyPrefix.'.');
        }
    }

    /**
     * Checks if we're about to validate an already-processing (object + prefix),
     * preventing infinite loops.
     *
     * @param  object  $object
     * @param  string  $prefix
     *
     * @return bool Returns false if this is a duplicate call (i.e. recursion).
     */
    private function checkRecursion(object $object, string $prefix) : bool {
        // Identify the combination (object + property prefix)
        $key = spl_object_hash($object).'::'.$prefix;
        if (isset($this->processing[$key])) {
            // We've already encountered this combination
            return false;
        }

        // Mark it as 'in processing'
        $this->processing[$key] = true;
        return true;
    }

    /**
     * Ends recursion check for a particular (object + prefix).
     */
    private function endRecursion(object $object, string $prefix) : void {
        $key = spl_object_hash($object).'::'.$prefix;
        unset($this->processing[$key]);
    }

    public function validateAll(object $object, string $propertyPrefix = '') : void {
        // Check recursion
        if (!$this->checkRecursion($object, $propertyPrefix)) {
            // If we're already processing this combination, skip.
            return;
        }

        try {
            $class = new ReflectionClass($object);
            $properties = $class->getProperties();
            $exceptions = [];
            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $type = $property->getType();
                $value = $property->isInitialized($object) ? $property->getValue($object) : null;

                $attributes = $property->getAttributes(ValidatorAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
                foreach ($attributes as $attributeReflection) {
                    /** @var ValidatorAttribute $attribute */
                    $attribute = $attributeReflection->newInstance();
                    try {
                        if (!$property->isInitialized($object) || ($value === null && $type?->allowsNull())) {
                            if ($attribute instanceof Required) {
                                $attribute->throw($object, $propertyPrefix.$propertyName);
                            }
                        }
                        else {
                            $attribute->validateValue($value, $object, $propertyName, $propertyPrefix);
                        }
                    } catch (ValidationException $e) {
                        $exceptions[] = $e;
                    }
                }

                if (is_object($value)) {
                    // Recursive validation
                    try {
                        $this->validateAll($value, $propertyPrefix.$propertyName.'.');
                    } catch (ValidationException $e) {
                        $exceptions[] = $e;
                    }
                }
            }

            if (count($exceptions) > 0) {
                throw new ValidationMultiException($exceptions);
            }
        } finally {
            $this->endRecursion($object, $propertyPrefix.'.');
        }
    }
}
