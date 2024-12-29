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
     * @param  object  $object
     * @return void
     * @throws ValidationException
     */
    public function validate(object $object, string $propertyPrefix = '') : void {
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
    }

    public function validateAll(object $object, string $propertyPrefix = '') : void {
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
    }
}
