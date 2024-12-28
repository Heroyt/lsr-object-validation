<?php

declare(strict_types=1);

namespace Mocks;

use Lsr\ObjectValidation\Attributes\Email;
use Lsr\ObjectValidation\Attributes\IntRange;
use Lsr\ObjectValidation\Attributes\Numeric;
use Lsr\ObjectValidation\Attributes\Required;
use Lsr\ObjectValidation\Attributes\StringLength;
use Lsr\ObjectValidation\Attributes\Uri;
use Lsr\ObjectValidation\Attributes\Url;

class ValidationUninitializedClass
{
    #[Required]
    public ?string $required;
}
