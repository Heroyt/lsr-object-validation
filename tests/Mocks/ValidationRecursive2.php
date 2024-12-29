<?php
declare(strict_types=1);

namespace Mocks;

use Lsr\ObjectValidation\Attributes\IntRange;
use Lsr\ObjectValidation\Attributes\Required;

class ValidationRecursive2
{

    #[Required]
    public string $name;

    #[Required, IntRange(min: 1)]
    public int $age;

    #[Required]
    public ValidationRecursive1 $validation;


}