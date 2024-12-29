<?php
declare(strict_types=1);

namespace Mocks;

use Lsr\ObjectValidation\Attributes\NoValidate;
use Lsr\ObjectValidation\Attributes\Required;

class NoValidateClass2
{

    #[Required]
    public string $property;

    public NoValidateClass $object;

}