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

class ValidationClass
{
    #[Required]
    public ?string $required;

    #[Email]
    public string $email;

    #[Numeric]
    public string|int|float $numeric;

    #[IntRange(min: 10)]
    public int $intMin;

    #[IntRange(max: 10)]
    public int $intMax;

    #[IntRange(min: 5, max: 10)]
    public int $intMinMax;

    #[IntRange]
    public int|string $intInvalid;

    #[StringLength(length: 10)]
    public string $strLen;

    #[StringLength(min: 10)]
    public string $strMin;

    #[StringLength(max: 10)]
    public string $strMax;

    #[StringLength(min: 5, max: 10)]
    public string $strMinMax;

    #[StringLength]
    public int|string $strInvalid;

    #[Uri]
    public string $uri;

    #[Url]
    public string $url;

    public function setEmail(string $email): string {
        $this->email = $email;
        return sprintf(
            '%s::$email - Must be a valid email. (value: %s)',
            $this::class,
            json_encode($email),
        );
    }

    public function setNumeric(string $numeric): string {
        $this->numeric = $numeric;
        return sprintf(
            '%s::$numeric - Must be numeric (string, int or float). (value: %s)',
            $this::class,
            json_encode($numeric),
        );
    }

    public function setIntMin(int $intMin): string {
        $this->intMin = $intMin;
        return sprintf(
            '%s::$intMin - Number must be larger then 10. (value: %s)',
            $this::class,
            json_encode($intMin),
        );
    }

    public function setIntMax(int $intMax): string {
        $this->intMax = $intMax;
        return sprintf(
            '%s::$intMax - Number must be lower then 10. (value: %s)',
            $this::class,
            json_encode($intMax),
        );
    }

    public function setIntMinMax(int $intMinMax): string {
        $this->intMinMax = $intMinMax;
        if ($intMinMax < 5) {
            return sprintf(
                '%s::$intMinMax - Number must be larger then 5. (value: %s)',
                $this::class,
                json_encode($intMinMax),
            );
        }
        return sprintf(
            '%s::$intMinMax - Number must be lower then 10. (value: %s)',
            $this::class,
            json_encode($intMinMax),
        );
    }

    public function setIntInvalid(string $intInvalid): string {
        $this->intInvalid = $intInvalid;
        return sprintf(
            '%s::$intInvalid - Must be an int. (value: %s)',
            $this::class,
            json_encode($intInvalid),
        );
    }

    public function setStrLen(string $strLen): string {
        $this->strLen = $strLen;
        return sprintf(
            '%s::$strLen - String length must be exactly 10. (value: %s)',
            $this::class,
            json_encode($strLen),
        );
    }

    public function setStrMin(string $strMin): string {
        $this->strMin = $strMin;
        return sprintf(
            '%s::$strMin - String length must be at least 10. (value: %s)',
            $this::class,
            json_encode($strMin),
        );
    }

    public function setStrMax(string $strMax): string {
        $this->strMax = $strMax;
        return sprintf(
            '%s::$strMax - String length must be at most 10. (value: %s)',
            $this::class,
            json_encode($strMax),
        );
    }

    public function setStrMinMax(string $strMinMax): string {
        $this->strMinMax = $strMinMax;
        return sprintf(
            '%s::$strMinMax - String length must be between 5 and 10. (value: %s)',
            $this::class,
            json_encode($strMinMax),
        );
    }
    public function setStrInvalid(int $strInvalid): string {
        $this->strInvalid = $strInvalid;
        return sprintf(
            '%s::$strInvalid - Must be a string. (value: %s)',
            $this::class,
            json_encode($strInvalid),
        );
    }

    public function setUri(string $uri): string {
        $this->uri = $uri;
        return sprintf(
            '%s::$uri - Must be a valid URI. (value: %s)',
            $this::class,
            json_encode($uri),
        );
    }

    public function setUrl(string $url): string {
        $this->url = $url;
        return sprintf(
            '%s::$url - Must be a valid URL. (value: %s)',
            $this::class,
            json_encode($url),
        );
    }
}
