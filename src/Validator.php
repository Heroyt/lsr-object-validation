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
     * @throws ValidationException
     * @return void
     */
    public function validate(object $object): void {
        $class = new ReflectionClass($object);
        $properties = $class->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $value = $property->isInitialized($object) ? $object->$propertyName : null;

            $attributes = $property->getAttributes(ValidatorAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attributeReflection) {
                /** @var ValidatorAttribute $attribute */
                $attribute = $attributeReflection->newInstance();
                if (!$property->isInitialized($object)) {
                    if ($attribute instanceof Required) {
                        $attribute->throw($object, $propertyName);
                    }
                }
                else {
                    $attribute->validateValue($value, $object, $propertyName);
                }
            }
        }
    }

    public function validateAll(object $object): void {
        $class = new ReflectionClass($object);
        $properties = $class->getProperties();
        $exceptions = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $value = $property->isInitialized($object) ? $object->$propertyName : null;

            $attributes = $property->getAttributes(ValidatorAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attributeReflection) {
                /** @var ValidatorAttribute $attribute */
                $attribute = $attributeReflection->newInstance();
                try {
                    if (!$property->isInitialized($object)) {
                        if ($attribute instanceof Required) {
                            $attribute->throw($object, $propertyName);
                        }
                    }
                    else {
                        $attribute->validateValue($value, $object, $propertyName);
                    }
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