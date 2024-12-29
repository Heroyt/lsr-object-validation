<?php
declare(strict_types=1);

namespace Mocks;

use Lsr\ObjectValidation\Attributes\NoValidate;
use Lsr\ObjectValidation\Attributes\Required;

class NoValidateClass1
{

    #[Required, NoValidate]
    public string $property;

    #[Required]
    public int $age;

}