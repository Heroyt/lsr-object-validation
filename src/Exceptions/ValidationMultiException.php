<?php

declare(strict_types=1);

namespace Lsr\ObjectValidation\Exceptions;

use InvalidArgumentException;

class ValidationMultiException extends ValidationException
{
    /**
     * @param  ValidationException[]  $exceptions
     */
    public function __construct(
        public readonly array $exceptions = [],
    ) {
        $messages = [];
        foreach ($this->exceptions as $exception) {
            /** @phpstan-ignore instanceof.alwaysTrue */
            if (!$exception instanceof ValidationException) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Argument #0 ($exceptions) of %s must be a list of %s objects.',
                        $this::class,
                        ValidationException::class
                    )
                );
            }
            $messages[] = $exception->getMessage();
        }

        parent::__construct(
            "Validation error:\n-" . implode("\n-", $messages)
        );
    }
}
