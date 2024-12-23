<?php
declare(strict_types=1);

namespace TestCases;

use Generator;
use Lsr\ObjectValidation\Attributes\Email;
use Lsr\ObjectValidation\Attributes\IntRange;
use Lsr\ObjectValidation\Attributes\Numeric;
use Lsr\ObjectValidation\Attributes\Required;
use Lsr\ObjectValidation\Attributes\StringLength;
use Lsr\ObjectValidation\Attributes\Uri;
use Lsr\ObjectValidation\Attributes\Url;
use Lsr\ObjectValidation\Exceptions\ValidationException;
use Lsr\ObjectValidation\Exceptions\ValidationMultiException;
use Lsr\ObjectValidation\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    public static function getTestValuesAll() : Generator {
        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setEmail('invalid email');
        yield [
            $obj,
            ['required', 'email'],
            $messages,
        ];

        $obj = new ValidationClass();
        $obj->required = null;
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setEmail('');
        yield [
            $obj,
            ['required', 'email'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setIntMin(0);
        $messages[] = $obj->setIntMax(99);
        $messages[] = $obj->setIntMinMax(0);
        $messages[] = $obj->setIntInvalid('invalid');
        yield [
            $obj,
            ['required', 'intMin', 'intMax', 'intMinMax', 'intInvalid'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setIntMin(0);
        $messages[] = $obj->setIntMax(99);
        $messages[] = $obj->setIntMinMax(99);
        $messages[] = $obj->setIntInvalid('invalid');
        yield [
            $obj,
            ['required', 'intMin', 'intMax', 'intMinMax', 'intInvalid'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setNumeric('');
        yield [
            $obj,
            ['required', 'numeric'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setStrLen('');
        $messages[] = $obj->setStrMin('');
        $messages[] = $obj->setStrMax('ajksdbakjfhbaksjfhbaksfjnaskfjans');
        $messages[] = $obj->setStrMinMax('ajksdbakjfhbaksjfhbaksfjnaskfjans');
        $messages[] = $obj->setStrInvalid(123);
        yield [
            $obj,
            ['required', 'strLen', 'strMin', 'strMax', 'strMinMax', 'strInvalid'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setStrLen('kasdjaksndnaksjdnaksudnaksjdnaskjd');
        $messages[] = $obj->setStrMin('mkada');
        $messages[] = $obj->setStrMax('ajksdbakjfhbaksjfhbaksfjnaskfjans');
        $messages[] = $obj->setStrMinMax('');
        $messages[] = $obj->setStrInvalid(123);
        yield [
            $obj,
            ['required', 'strLen', 'strMin', 'strMax', 'strMinMax', 'strInvalid'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setNumeric('');
        yield [
            $obj,
            ['required', 'numeric'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setUrl('');
        yield [
            $obj,
            ['required', 'url'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setUrl('htp:/example.com');
        yield [
            $obj,
            ['required', 'url'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setUrl('https://example.com/valid');
        yield [
            $obj,
            ['required'],
            $messages,
        ];

        $obj = new ValidationClass();
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setUri('example');
        yield [
            $obj,
            ['required', 'uri'],
            $messages,
        ];

        $obj = new ValidationClass();
        $obj->required = null;
        $messages = [
            sprintf('%s::$required - Is required.', $obj::class),
        ];
        $messages[] = $obj->setUri('urn:isbn:0451450523');
        yield [
            $obj,
            ['required'],
            $messages,
        ];
    }

    public static function getTestValues() : Generator {
        $obj = new ValidationClass();
        $obj->required = 'asda';
        $message = $obj->setEmail('invalid email');
        yield [
            $obj,
            'email',
            $message,
        ];

        $obj = new ValidationClass();
        $obj->required = 'asda';
        $message = $obj->setEmail('');
        yield [
            $obj,
            'email',
            $message,
        ];

        $obj = new ValidationClass();
        $obj->required = 'asda';
        $message = $obj->setEmail('almost@email');
        yield [
            $obj,
            'email',
            $message,
        ];

        $obj = new ValidationClass();
        $obj->required = 'asda';
        $message = $obj->setNumeric('');
        yield [
            $obj,
            'numeric',
            $message,
        ];

        $obj = new ValidationClass();
        $obj->required = 'asda';
        $message = $obj->setNumeric('Hello!');
        yield [
            $obj,
            'numeric',
            $message,
        ];

        $obj = new ValidationClass();
        yield [
            $obj,
            'required',
            sprintf('%s::$required - Is required.', $obj::class),
        ];

        $obj = new ValidationClass();
        $obj->required = null;
        yield [
            $obj,
            'required',
            sprintf('%s::$required - Is required.', $obj::class),
        ];

        $obj = new ValidationClass();
        $obj->required = 'some value';
        $message = $obj->setUrl('htp://invalid-url');
        yield [
            $obj,
            'url',
            $message,
        ];

        $obj = new ValidationClass();
        $obj->required = 'some value';
        $message = $obj->setUrl('');
        yield [
            $obj,
            'url',
            $message,
        ];

        $obj = new ValidationClass();
        $obj->required = 'some value';
        $message = $obj->setUri('example.com');
        yield [
            $obj,
            'uri',
            $message,
        ];
    }

    public static function getTestValuesValid() : Generator {
        $obj = new ValidationClass();
        $obj->required = 'asda';

        yield [$obj];

        $obj->email = 'email@email.com';
        yield [$obj];

        $obj->numeric = '15';
        yield [$obj];

        $obj->numeric = 15;
        yield [$obj];

        $obj->numeric = 1.0;
        yield [$obj];

        $obj->intMin = 50;
        yield [$obj];

        $obj->intMax = 0;
        yield [$obj];

        $obj->intMinMax = 5;
        yield [$obj];

        $obj->intMinMax = 10;
        yield [$obj];

        $obj->intMinMax = 7;
        yield [$obj];

        $obj->intInvalid = 5;
        yield [$obj];

        $obj->strLen = 'askdjaskdj';
        yield [$obj];

        $obj->strMin = 'askdjaskdj';
        yield [$obj];

        $obj->strMin = 'askdjaskdjaljksndakjsd';
        yield [$obj];

        $obj->strMax = 'askdjaskdj';
        yield [$obj];

        $obj->strMax = 'askd';
        yield [$obj];

        $obj->strMinMax = 'askdjaskdj';
        yield [$obj];

        $obj->strMinMax = 'askdj';
        yield [$obj];

        $obj->strMinMax = 'askdjasd';
        yield [$obj];

        $obj->strInvalid = 'askdjasd';
        yield [$obj];

        $obj->url = 'https://mywebsite.com/path?query=param';
        yield [$obj];

        $obj->uri = 'urn:isbn:0451450523';
        yield [$obj];

        $obj->url = 'http://localhost:8080';
        yield [$obj];
    }

    #[DataProvider('getTestValuesAll')]
    public function testValidateAll(
        ValidationClass $object,
        array           $invalidProperties,
        array           $exceptionMessages
    ) : void {
        $validator = new Validator();

        try {
            $validator->validateAll($object);
        } catch (ValidationMultiException $e) {
            $this->assertCount(
                count($invalidProperties),
                $e->exceptions,
                'Invalid exception count: '.json_encode(array_map(static fn(ValidationException $exception) => $exception->property, $e->exceptions))
            );
            foreach ($e->exceptions as $key => $exception) {
                $this->assertEquals($invalidProperties[$key], $exception->property);
                $this->assertEquals($exceptionMessages[$key], $exception->getMessage());
            }
        }
    }

    #[DataProvider('getTestValues')]
    public function testValidate(ValidationClass $object, string $invalidProperty, string $exceptionMessage) : void {
        $validator = new Validator();

        try {
            $validator->validate($object);
        } catch (ValidationException $e) {
            $this->assertEquals($invalidProperty, $e->property);
            $this->assertEquals($exceptionMessage, $e->getMessage());
        }
    }

    #[DataProvider('getTestValuesValid'), DoesNotPerformAssertions]
    public function testValidateValid(ValidationClass $object) : void {
        $validator = new Validator();

        $validator->validate($object);
        $validator->validateAll($object);
    }
}

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

    public function setEmail(string $email) : string {
        $this->email = $email;
        return sprintf(
            '%s::$email - Must be a valid email. (value: %s)',
            $this::class,
            json_encode($email),
        );
    }

    public function setNumeric(string $numeric) : string {
        $this->numeric = $numeric;
        return sprintf(
            '%s::$numeric - Must be numeric (string, int or float). (value: %s)',
            $this::class,
            json_encode($numeric),
        );
    }

    public function setIntMin(int $intMin) : string {
        $this->intMin = $intMin;
        return sprintf(
            '%s::$intMin - Number must be larger then 10. (value: %s)',
            $this::class,
            json_encode($intMin),
        );
    }

    public function setIntMax(int $intMax) : string {
        $this->intMax = $intMax;
        return sprintf(
            '%s::$intMax - Number must be lower then 10. (value: %s)',
            $this::class,
            json_encode($intMax),
        );
    }

    public function setIntMinMax(int $intMinMax) : string {
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

    public function setIntInvalid(string $intInvalid) : string {
        $this->intInvalid = $intInvalid;
        return sprintf(
            '%s::$intInvalid - Must be an int. (value: %s)',
            $this::class,
            json_encode($intInvalid),
        );
    }

    public function setStrLen(string $strLen) : string {
        $this->strLen = $strLen;
        return sprintf(
            '%s::$strLen - String length must be exactly 10. (value: %s)',
            $this::class,
            json_encode($strLen),
        );
    }

    public function setStrMin(string $strMin) : string {
        $this->strMin = $strMin;
        return sprintf(
            '%s::$strMin - String length must be at least 10. (value: %s)',
            $this::class,
            json_encode($strMin),
        );
    }

    public function setStrMax(string $strMax) : string {
        $this->strMax = $strMax;
        return sprintf(
            '%s::$strMax - String length must be at most 10. (value: %s)',
            $this::class,
            json_encode($strMax),
        );
    }

    public function setStrMinMax(string $strMinMax) : string {
        $this->strMinMax = $strMinMax;
        return sprintf(
            '%s::$strMinMax - String length must be between 5 and 10. (value: %s)',
            $this::class,
            json_encode($strMinMax),
        );
    }
    public function setStrInvalid(int $strInvalid) : string {
        $this->strInvalid = $strInvalid;
        return sprintf(
            '%s::$strInvalid - Must be a string. (value: %s)',
            $this::class,
            json_encode($strInvalid),
        );
    }

    public function setUri(string $uri) : string {
        $this->uri = $uri;
        return sprintf(
            '%s::$uri - Must be a valid URI. (value: %s)',
            $this::class,
            json_encode($uri),
        );
    }

    public function setUrl(string $url) : string {
        $this->url = $url;
        return sprintf(
            '%s::$url - Must be a valid URL. (value: %s)',
            $this::class,
            json_encode($url),
        );
    }

}
