<?php

declare(strict_types=1);

namespace Lsr\ObjectValidation;

use Lsr\ObjectValidation\Attributes\NoValidate;
use Lsr\ObjectValidation\Attributes\Required;
use Lsr\ObjectValidation\Attributes\Validator as ValidatorAttribute;
use Lsr\ObjectValidation\Exceptions\ValidationException;
use Lsr\ObjectValidation\Exceptions\ValidationMultiException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;

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
        if (!$this->checkRecursion($object)) {
            // If we're already processing this combination, skip.
            return;
        }

        try {
            $class = new ReflectionClass($object);
            $noValidate = $class->getAttributes(NoValidate::class);
            if (count($noValidate) > 0) {
                $this->endRecursion($object);
                return;
            }

            $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
            foreach ($properties as $property) {
                $noValidate = $property->getAttributes(NoValidate::class);
                if (count($noValidate) > 0) {
                    continue;
                }

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
            $this->endRecursion($object);
        }
    }

    /**
     * Checks if we're about to validate an already-processing (object),
     * preventing infinite loops.
     *
     * @param  object  $object
     *
     * @return bool Returns false if this is a duplicate call (i.e. recursion).
     */
    private function checkRecursion(object $object) : bool {
        $key = spl_object_hash($object);
        if (isset($this->processing[$key])) {
            // We've already encountered this combination
            return false;
        }

        // Mark it as 'in processing'
        $this->processing[$key] = true;
        return true;
    }

    /**
     * Ends recursion check for a particular (object).
     */
    private function endRecursion(object $object) : void {
        $key = spl_object_hash($object);
        unset($this->processing[$key]);
    }

    public function validateAll(object $object, string $propertyPrefix = '') : void {
        // Check recursion
        if (!$this->checkRecursion($object)) {
            // If we're already processing this combination, skip.
            return;
        }

        try {
            $class = new ReflectionClass($object);
            $noValidate = $class->getAttributes(NoValidate::class);
            if (count($noValidate) > 0) {
                $this->endRecursion($object);
                return;
            }

            $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
            $exceptions = [];
            foreach ($properties as $property) {
                $noValidate = $property->getAttributes(NoValidate::class);
                if (count($noValidate) > 0) {
                    continue;
                }

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

                if (is_object($value) && !$property->isVirtual() && !$property->hasHooks()) {
                    // Recursive validation for non-virtual properties
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
            $this->endRecursion($object);
        }
    }
}
