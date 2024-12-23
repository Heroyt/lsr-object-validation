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
use Mocks\ValidationClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public static function getTestValuesAll(): Generator {
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

    public static function getTestValues(): Generator {
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

    public static function getTestValuesValid(): Generator {
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
    ): void {
        $validator = new Validator();

        try {
            $validator->validateAll($object);
        } catch (ValidationMultiException $e) {
            $this->assertCount(
                count($invalidProperties),
                $e->exceptions,
                'Invalid exception count: ' . json_encode(
                    array_map(
                        static fn(ValidationException $exception) => $exception->property,
                        $e->exceptions
                    )
                )
            );
            foreach ($e->exceptions as $key => $exception) {
                $this->assertEquals($invalidProperties[$key], $exception->property);
                $this->assertEquals($exceptionMessages[$key], $exception->getMessage());
            }
        }
    }

    #[DataProvider('getTestValues')]
    public function testValidate(ValidationClass $object, string $invalidProperty, string $exceptionMessage): void {
        $validator = new Validator();

        try {
            $validator->validate($object);
        } catch (ValidationException $e) {
            $this->assertEquals($invalidProperty, $e->property);
            $this->assertEquals($exceptionMessage, $e->getMessage());
        }
    }

    #[DataProvider('getTestValuesValid'), DoesNotPerformAssertions]
    public function testValidateValid(ValidationClass $object): void {
        $validator = new Validator();

        $validator->validate($object);
        $validator->validateAll($object);
    }
}
