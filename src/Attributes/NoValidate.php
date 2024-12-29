<?php
declare(strict_types=1);

namespace Lsr\ObjectValidation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class NoValidate extends Attribute
{

}