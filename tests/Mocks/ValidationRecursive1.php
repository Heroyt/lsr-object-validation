<?php
declare(strict_types=1);

namespace Mocks;

use Lsr\ObjectValidation\Attributes\Required;

class ValidationRecursive1
{

    #[Required]
    public string $name;

    public ValidationRecursive2 $object;
}