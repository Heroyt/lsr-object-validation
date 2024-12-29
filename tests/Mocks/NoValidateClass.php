<?php
declare(strict_types=1);

namespace Mocks;

use Lsr\ObjectValidation\Attributes\NoValidate;
use Lsr\ObjectValidation\Attributes\Required;

#[NoValidate]
class NoValidateClass
{

    #[Required]
    public string $property;

}